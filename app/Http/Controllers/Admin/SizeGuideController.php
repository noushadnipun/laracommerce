<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product\ProductSizeGuide;
use Illuminate\Http\Request;

class SizeGuideController extends Controller
{
    /**
     * Display a listing of size guides
     */
    public function index(Request $request)
    {
        try {
            $query = ProductSizeGuide::with('product');
            
            // Filter by product
            if ($request->has('product_id') && $request->product_id) {
                $query->where('product_id', $request->product_id);
            }
            
            // Filter by size type
            if ($request->has('size_type') && $request->size_type) {
                $query->where('size_type', $request->size_type);
            }
            
            // Filter by status
            if ($request->has('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }
            
            $sizeGuides = $query->orderBy('created_at', 'desc')->paginate(20);
            $products = Product::select('id', 'title')->get();
            
            return view('admin.size-guide.index', compact('sizeGuides', 'products'));
            
        } catch (\Exception $e) {
            // For debugging - remove this later
            dd('Size Guide Controller Error: ' . $e->getMessage(), $e->getTraceAsString());
        }
    }
    
    /**
     * Show the form for creating a new size guide
     */
    public function create()
    {
        $products = Product::select('id', 'title')->get();
        $sizeTypes = ['clothing', 'shoes', 'accessories', 'jewelry', 'bags'];
        
        return view('admin.size-guide.create', compact('products', 'sizeTypes'));
    }
    
    /**
     * Store a newly created size guide
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'measurement_guide' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        // Validate size chart data
        $sizeChart = $request->input('size_chart');
        if (!$sizeChart || !is_array($sizeChart)) {
            return back()->withErrors(['size_chart' => 'Size chart data is required.'])->withInput();
        }
        
        $sizeGuide = ProductSizeGuide::create([
            'product_id' => $request->product_id,
            'size_type' => $request->size_type,
            'title' => $request->title,
            'description' => $request->description,
            'size_chart' => $sizeChart,
            'measurement_guide' => $request->measurement_guide,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('admin_size_guide_index')
            ->with('success', 'Size guide created successfully');
    }
    
    /**
     * Show the form for editing a size guide
     */
    public function edit($id)
    {
        $sizeGuide = ProductSizeGuide::with('product')->findOrFail($id);
        $products = Product::select('id', 'title')->get();
        $sizeTypes = ['clothing', 'shoes', 'accessories', 'jewelry', 'bags'];
        
        return view('admin.size-guide.edit', compact('sizeGuide', 'products', 'sizeTypes'));
    }
    
    /**
     * Update the specified size guide
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'measurement_guide' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $sizeGuide = ProductSizeGuide::findOrFail($id);
        
        // Validate size chart data
        $sizeChart = $request->input('size_chart');
        if (!$sizeChart || !is_array($sizeChart)) {
            return back()->withErrors(['size_chart' => 'Size chart data is required.'])->withInput();
        }
        
        $sizeGuide->update([
            'product_id' => $request->product_id,
            'size_type' => $request->size_type,
            'title' => $request->title,
            'description' => $request->description,
            'size_chart' => $sizeChart,
            'measurement_guide' => $request->measurement_guide,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('admin_size_guide_index')
            ->with('success', 'Size guide updated successfully');
    }
    
    /**
     * Remove the specified size guide
     */
    public function destroy($id)
    {
        $sizeGuide = ProductSizeGuide::findOrFail($id);
        $sizeGuide->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Size guide deleted successfully'
        ]);
    }
    
    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        $sizeGuide = ProductSizeGuide::findOrFail($id);
        $sizeGuide->update(['is_active' => !$sizeGuide->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'is_active' => $sizeGuide->is_active
        ]);
    }
    
    /**
     * Get default size chart for a type
     */
    public function getDefaultChart(Request $request)
    {
        $sizeType = $request->input('size_type', 'clothing');
        
        if ($sizeType === 'clothing') {
            $chart = ProductSizeGuide::createDefaultClothingChart();
        } elseif ($sizeType === 'shoes') {
            $chart = ProductSizeGuide::createDefaultShoeChart();
        } else {
            $chart = [
                'sizes' => ['S', 'M', 'L'],
                'measurements' => [
                    'size' => ['Small', 'Medium', 'Large']
                ],
                'units' => 'standard'
            ];
        }
        
        return response()->json($chart);
    }
}
