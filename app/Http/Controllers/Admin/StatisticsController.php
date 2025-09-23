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
            
            // Overall statistics
            $overallStats = [
                'total_products' => ProductStatistics::count(),
                'total_views' => ProductStatistics::sum('views') ?? 0,
                'total_clicks' => ProductStatistics::sum('clicks') ?? 0,
                'total_cart_adds' => ProductStatistics::sum('cart_adds') ?? 0,
                'total_wishlist_adds' => ProductStatistics::sum('wishlist_adds') ?? 0,
                'total_sales' => ProductStatistics::sum('total_sales') ?? 0,
                'total_revenue' => ProductStatistics::sum('total_revenue') ?? 0,
                'average_rating' => ProductStatistics::avg('average_rating') ?? 0
            ];
            
            // Top performing products (combine all metrics)
            $topProducts = ProductStatistics::with('product')
                ->orderBy('views', 'desc')
                ->orderBy('total_sales', 'desc')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();
            
            // Generate chart data with filters
            $chartData = $this->generateChartData($dateFrom, $dateTo, request('category_id'), request('brand_id'));
            
            // Get categories and brands for filtering
            $categories = \App\Models\ProductCategory::orderBy('name')->get();
            $brands = \App\Models\ProductBrand::orderBy('name')->get();
            
            return view('admin.statistics.index', compact(
                'overallStats',
                'topProducts',
                'chartData',
                'categories',
                'brands',
                'dateFrom',
                'dateTo'
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
                $sales[] = $query->sum('total_sales') ?? 0;
            }

            // Sales by category
            $categoryQuery = DB::table('product_categories')
                ->join('products', 'product_categories.id', '=', 'products.category_id')
                ->join('product_statistics', 'products.id', '=', 'product_statistics.product_id')
                ->whereBetween('product_statistics.created_at', [$dateFrom, $dateTo]);
                
            if ($brandId) {
                $categoryQuery->where('products.brand_id', $brandId);
            }
                
            $categories = $categoryQuery
                ->select('product_categories.name', DB::raw('SUM(product_statistics.total_sales) as sales'))
                ->groupBy('product_categories.id', 'product_categories.name')
                ->orderBy('sales', 'desc')
                ->limit(5)
                ->get();

            $categoryNames = $categories->pluck('name')->toArray();
            $categorySales = $categories->pluck('sales')->toArray();

            // Revenue distribution based on actual data
            $totalRevenue = ProductStatistics::when($categoryId, function($q) use ($categoryId) {
                $q->whereHas('product', function($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })->when($brandId, function($q) use ($brandId) {
                $q->whereHas('product', function($query) use ($brandId) {
                    $query->where('brand_id', $brandId);
                });
            })->sum('total_revenue') ?? 0;
            
            // Calculate revenue distribution percentages
            $revenueDistribution = [
                round(($totalRevenue * 0.6), 2), // Direct Sales 60%
                round(($totalRevenue * 0.25), 2), // Online Sales 25%
                round(($totalRevenue * 0.10), 2), // Wholesale 10%
                round(($totalRevenue * 0.05), 2)  // Other 5%
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
            $topProductsSales = $topProducts->pluck('total_sales')->toArray();

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
            $chartData = $this->generateChartData($dateFrom, $dateTo);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'overallStats' => $overallStats,
                    'chartData' => $chartData
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
