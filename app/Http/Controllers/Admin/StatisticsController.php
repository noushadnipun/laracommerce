<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Display product statistics dashboard
     */
    public function index(Request $request)
    {
        try {
            // Get date range
            $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', now()->format('Y-m-d'));
            
            // Overall statistics - combine ProductStatistics and real order data
            $overallStats = $this->getOverallStats($dateFrom, $dateTo);
            
            // Top performing products - combine interaction stats with real sales
            $topProducts = $this->getTopProducts($dateFrom, $dateTo);
            
            // Generate chart data with filters
            $chartData = $this->generateChartData($dateFrom, $dateTo, request('category_id'), request('brand_id'));
            
            // Get categories and brands for filtering
            $categories = \App\Models\ProductCategory::orderBy('name')->get();
            $brands = \App\Models\ProductBrand::orderBy('name')->get();
            
            // Get recent sales data for sell report
            $recentSales = $this->getRecentSales($dateFrom, $dateTo);
            
            // Get sales analytics
            $salesAnalytics = $this->getSalesAnalytics($dateFrom, $dateTo);
            
            return view('admin.statistics.index', compact(
                'overallStats',
                'topProducts',
                'chartData',
                'categories',
                'brands',
                'dateFrom',
                'dateTo',
                'recentSales',
                'salesAnalytics'
            ));
            
        } catch (\Exception $e) {
            // For debugging - remove this later
            dd('Statistics Controller Error: ' . $e->getMessage(), $e->getTraceAsString());
        }
    }

    /**
     * Generate chart data for statistics
     */
    private function generateChartData($dateFrom, $dateTo, $categoryId = null, $brandId = null)
    {
        try {
            // Views over time (last 30 days)
            $dates = [];
            $views = [];
            $clicks = [];
            $sales = [];
            
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dates[] = now()->subDays($i)->format('M d');
                
                // Get views for this date
                $query = ProductStatistics::whereDate('created_at', $date);
                if ($categoryId) {
                    $query->whereHas('product', function($q) use ($categoryId) {
                        $q->where('category_id', $categoryId);
                    });
                }
                if ($brandId) {
                    $query->whereHas('product', function($q) use ($brandId) {
                        $q->where('brand_id', $brandId);
                    });
                }
                
                $views[] = $query->sum('views') ?? 0;
                $clicks[] = $query->sum('clicks') ?? 0;
                
                // Get real sales data from orders for this date
                $realSalesQuery = DB::table('product_order_details')
                    ->join('product_orders', 'product_order_details.order_id', '=', 'product_orders.id')
                    ->join('products', 'product_order_details.product_id', '=', 'products.id')
                    ->whereDate('product_orders.created_at', $date)
                    ->where('product_orders.payment_status', 'Paid');
                    
                if ($categoryId) {
                    $realSalesQuery->where('products.category_id', $categoryId);
                }
                if ($brandId) {
                    $realSalesQuery->where('products.brand_id', $brandId);
                }
                
                $realSales = $realSalesQuery->sum('product_order_details.qty') ?? 0;
                $sales[] = $realSales;
            }

            // Sales by category - use real order data
            $categoryQuery = DB::table('product_categories')
                ->join('products', 'product_categories.id', '=', 'products.category_id')
                ->join('product_order_details', 'products.id', '=', 'product_order_details.product_id')
                ->join('product_orders', 'product_order_details.order_id', '=', 'product_orders.id')
                ->whereBetween('product_orders.created_at', [$dateFrom, $dateTo])
                ->where('product_orders.payment_status', 'Paid');
                
            if ($brandId) {
                $categoryQuery->where('products.brand_id', $brandId);
            }
                
            $categories = $categoryQuery
                ->select('product_categories.name', DB::raw('SUM(product_order_details.qty) as sales'))
                ->groupBy('product_categories.id', 'product_categories.name')
                ->orderBy('sales', 'desc')
                ->limit(5)
                ->get();

            $categoryNames = $categories->pluck('name')->toArray();
            $categorySales = $categories->pluck('sales')->toArray();

            // Revenue distribution based on actual order data
            $totalRevenue = DB::table('product_orders')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('payment_status', 'Paid')
                ->sum('final_amount') ?? 0;
            
            $onlineRevenue = DB::table('product_orders')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('payment_status', 'Paid')
                ->where('payment_type', 'SSLCommerz')
                ->sum('final_amount') ?? 0;
                
            $codRevenue = DB::table('product_orders')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('payment_status', 'Paid')
                ->where('payment_type', 'COD')
                ->sum('final_amount') ?? 0;
                
            $pendingRevenue = DB::table('product_orders')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('payment_status', 'Pending')
                ->sum('final_amount') ?? 0;
                
            $otherRevenue = DB::table('product_orders')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('payment_status', 'Paid')
                ->whereNotIn('payment_type', ['SSLCommerz', 'COD'])
                ->sum('final_amount') ?? 0;
            
            // Calculate revenue distribution percentages
            $revenueDistribution = [
                $onlineRevenue,
                $codRevenue, 
                $pendingRevenue,
                $otherRevenue
            ];

            // Top products for radar chart
            $topProductsQuery = ProductStatistics::with('product')
                ->whereBetween('created_at', [$dateFrom, $dateTo]);
                
            if ($categoryId) {
                $topProductsQuery->whereHas('product', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            if ($brandId) {
                $topProductsQuery->whereHas('product', function($q) use ($brandId) {
                    $q->where('brand_id', $brandId);
                });
            }
            
            $topProducts = $topProductsQuery
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get();

            $topProductsLabels = $topProducts->pluck('product.title')->toArray();
            $topProductsViews = $topProducts->pluck('views')->toArray();
            
            // Get real sales data for top products
            $topProductsSales = [];
            foreach ($topProducts as $product) {
                $realSales = DB::table('product_order_details')
                    ->join('product_orders', 'product_order_details.order_id', '=', 'product_orders.id')
                    ->where('product_order_details.product_id', $product->product_id)
                    ->whereBetween('product_orders.created_at', [$dateFrom, $dateTo])
                    ->where('product_orders.payment_status', 'Paid')
                    ->sum('product_order_details.qty') ?? 0;
                $topProductsSales[] = $realSales;
            }

            return [
                'dates' => $dates,
                'views' => $views,
                'clicks' => $clicks,
                'sales' => $sales,
                'categories' => $categoryNames,
                'category_sales' => $categorySales,
                'revenue_distribution' => $revenueDistribution,
                'top_products_labels' => $topProductsLabels,
                'top_products_views' => $topProductsViews,
                'top_products_sales' => $topProductsSales
            ];

        } catch (\Exception $e) {
            return [
                'dates' => [],
                'views' => [],
                'clicks' => [],
                'sales' => [],
                'categories' => [],
                'category_sales' => [],
                'revenue_distribution' => [0, 0, 0, 0],
                'top_products_labels' => [],
                'top_products_views' => [],
                'top_products_sales' => []
            ];
        }
    }
    
    /**
     * Get detailed product statistics
     */
    public function productStats($id)
    {
        $statistics = ProductStatistics::with('product')->findOrFail($id);
        $product = $statistics->product;
        
        return view('admin.statistics.product', compact('product', 'statistics'));
    }
    
    /**
     * Export statistics data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        $stats = ProductStatistics::with('product')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('views', 'desc')
            ->get();
        
        if ($format === 'csv') {
            return $this->exportToCsv($stats);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($stats);
        }
        
        return back()->withErrors(['format' => 'Invalid export format']);
    }
    
    /**
     * Export to CSV
     */
    private function exportToCsv($stats)
    {
        $filename = 'product_statistics_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Product ID',
                'Product Title',
                'Views',
                'Clicks',
                'Cart Adds',
                'Wishlist Adds',
                'Compare Adds',
                'Shares',
                'Reviews Count',
                'Average Rating',
                'Total Sales',
                'Total Revenue',
                'Last Viewed',
                'Last Sold'
            ]);
            
            // CSV data
            foreach ($stats as $stat) {
                fputcsv($file, [
                    $stat->product_id,
                    $stat->product->title ?? 'N/A',
                    $stat->views,
                    $stat->clicks,
                    $stat->cart_adds,
                    $stat->wishlist_adds,
                    $stat->compare_adds,
                    $stat->shares,
                    $stat->reviews_count,
                    $stat->average_rating,
                    $stat->total_sales,
                    $stat->total_revenue,
                    $stat->last_viewed_at ? $stat->last_viewed_at->format('Y-m-d H:i:s') : 'Never',
                    $stat->last_sold_at ? $stat->last_sold_at->format('Y-m-d H:i:s') : 'Never'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export to Excel (placeholder)
     */
    private function exportToExcel($stats)
    {
        // This would require Laravel Excel package
        return back()->with('info', 'Excel export requires Laravel Excel package');
    }
    
    /**
     * Get statistics API data
     */
    public function api(Request $request)
    {
        try {
            $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', now()->format('Y-m-d'));
            $categoryId = $request->get('category_id');
            $brandId = $request->get('brand_id');
            
            // Build query with filters
            $query = ProductStatistics::query();
            
            if ($categoryId) {
                $query->whereHas('product', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            
            if ($brandId) {
                $query->whereHas('product', function($q) use ($brandId) {
                    $q->where('brand_id', $brandId);
                });
            }
            
            $overallStats = [
                'total_products' => $query->count(),
                'total_views' => $query->sum('views') ?? 0,
                'total_clicks' => $query->sum('clicks') ?? 0,
                'total_cart_adds' => $query->sum('cart_adds') ?? 0,
                'total_wishlist_adds' => $query->sum('wishlist_adds') ?? 0,
                'total_sales' => $query->sum('total_sales') ?? 0,
                'total_revenue' => $query->sum('total_revenue') ?? 0,
                'average_rating' => $query->avg('average_rating') ?? 0
            ];
            
            // Generate chart data
            $chartData = $this->generateChartData($dateFrom, $dateTo, $categoryId, $brandId);
            
            // Get recent sales and analytics
            $recentSales = $this->getRecentSales($dateFrom, $dateTo);
            $salesAnalytics = $this->getSalesAnalytics($dateFrom, $dateTo);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'overallStats' => $overallStats,
                    'chartData' => $chartData,
                    'recentSales' => $recentSales,
                    'salesAnalytics' => $salesAnalytics
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get overall statistics combining ProductStatistics and real order data
     */
    private function getOverallStats($dateFrom, $dateTo)
    {
        // Product interaction statistics
        $productStats = [
            'total_products' => ProductStatistics::count(),
            'total_views' => ProductStatistics::sum('views') ?? 0,
            'total_clicks' => ProductStatistics::sum('clicks') ?? 0,
            'total_cart_adds' => ProductStatistics::sum('cart_adds') ?? 0,
            'total_wishlist_adds' => ProductStatistics::sum('wishlist_adds') ?? 0,
            'average_rating' => ProductStatistics::avg('average_rating') ?? 0
        ];
        
        // Real order statistics
        $orderStats = DB::table('product_orders')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(final_amount) as total_revenue,
                SUM(CASE WHEN payment_status = "Paid" THEN final_amount ELSE 0 END) as paid_revenue,
                SUM(CASE WHEN order_status = "delivered" THEN final_amount ELSE 0 END) as delivered_revenue,
                AVG(final_amount) as average_order_value
            ')
            ->first();
            
        // Real sales count from order details
        $salesStats = DB::table('product_order_details')
            ->join('product_orders', 'product_order_details.order_id', '=', 'product_orders.id')
            ->whereBetween('product_orders.created_at', [$dateFrom, $dateTo])
            ->selectRaw('SUM(qty) as total_units_sold')
            ->first();
        
        return array_merge($productStats, [
            'total_orders' => $orderStats->total_orders ?? 0,
            'total_revenue' => $orderStats->total_revenue ?? 0,
            'paid_revenue' => $orderStats->paid_revenue ?? 0,
            'delivered_revenue' => $orderStats->delivered_revenue ?? 0,
            'average_order_value' => $orderStats->average_order_value ?? 0,
            'total_units_sold' => $salesStats->total_units_sold ?? 0,
            'total_sales' => $salesStats->total_units_sold ?? 0, // For compatibility
        ]);
    }
    
    /**
     * Get top products combining interaction stats with real sales
     */
    private function getTopProducts($dateFrom, $dateTo)
    {
        // Get products with their interaction statistics
        $productStats = ProductStatistics::with('product')
            ->whereHas('product')
            ->get()
            ->keyBy('product_id');
            
        // Get real sales data from orders
        $salesData = DB::table('product_order_details')
            ->join('product_orders', 'product_order_details.order_id', '=', 'product_orders.id')
            ->join('products', 'product_order_details.product_id', '=', 'products.id')
            ->whereBetween('product_orders.created_at', [$dateFrom, $dateTo])
            ->where('product_orders.payment_status', 'Paid')
            ->selectRaw('
                product_order_details.product_id,
                SUM(product_order_details.qty) as units_sold,
                SUM(product_order_details.price) as revenue_generated,
                COUNT(DISTINCT product_order_details.order_id) as orders_count
            ')
            ->groupBy('product_order_details.product_id')
            ->get()
            ->keyBy('product_id');
        
        // Combine the data
        $combinedData = collect();
        
        foreach ($productStats as $productId => $stats) {
            $sales = $salesData->get($productId);
            $combinedData->push((object) [
                'id' => $productId,
                'product' => $stats->product,
                'views' => $stats->views ?? 0,
                'clicks' => $stats->clicks ?? 0,
                'cart_adds' => $stats->cart_adds ?? 0,
                'wishlist_adds' => $stats->wishlist_adds ?? 0,
                'average_rating' => $stats->average_rating ?? 0,
                'total_sales' => $sales->units_sold ?? 0,
                'total_revenue' => $sales->revenue_generated ?? 0,
                'orders_count' => $sales->orders_count ?? 0,
                'conversion_rate' => $stats->views > 0 ? (($sales->units_sold ?? 0) / $stats->views) * 100 : 0
            ]);
        }
        
        return $combinedData
            ->sortByDesc(function($item) {
                // Weighted score: 40% revenue, 30% sales, 20% views, 10% conversion rate
                return ($item->total_revenue * 0.4) + 
                       ($item->total_sales * 0.3) + 
                       ($item->views * 0.2) + 
                       ($item->conversion_rate * 0.1);
            })
            ->take(10)
            ->values();
    }
    
    /**
     * Get recent sales data for sell report
     */
    private function getRecentSales($dateFrom, $dateTo)
    {
        return \App\Models\ProductOrder::whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }
    
    /**
     * Get comprehensive sales analytics
     */
    private function getSalesAnalytics($dateFrom, $dateTo)
    {
        // Daily sales trend
        $dailySales = DB::table('product_orders')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_status', 'Paid')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(final_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Payment method breakdown
        $paymentMethods = DB::table('product_orders')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_status', 'Paid')
            ->selectRaw('payment_type, COUNT(*) as count, SUM(final_amount) as revenue')
            ->groupBy('payment_type')
            ->get();
            
        // Order status breakdown
        $orderStatuses = DB::table('product_orders')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('order_status, COUNT(*) as count, SUM(final_amount) as revenue')
            ->groupBy('order_status')
            ->get();
            
        // Top selling products by revenue
        $topSellingProducts = DB::table('product_order_details')
            ->join('product_orders', 'product_order_details.order_id', '=', 'product_orders.id')
            ->join('products', 'product_order_details.product_id', '=', 'products.id')
            ->whereBetween('product_orders.created_at', [$dateFrom, $dateTo])
            ->where('product_orders.payment_status', 'Paid')
            ->selectRaw('
                products.id,
                products.title,
                SUM(product_order_details.qty) as units_sold,
                SUM(product_order_details.price) as revenue
            ')
            ->groupBy('products.id', 'products.title')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get();
            
        // Customer analytics
        $customerStats = DB::table('product_orders')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('user_id')
            ->selectRaw('
                COUNT(DISTINCT user_id) as unique_customers,
                AVG(final_amount) as avg_order_value,
                COUNT(*) as total_orders
            ')
            ->first();
            
        return [
            'daily_sales' => $dailySales,
            'payment_methods' => $paymentMethods,
            'order_statuses' => $orderStatuses,
            'top_selling_products' => $topSellingProducts,
            'customer_stats' => $customerStats
        ];
    }
}
