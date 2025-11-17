<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all products, get the latest ones first, and paginate
        // $products = Product::latest()->paginate(10);

        $products = Product::with('category')->latest()->paginate(10);

        // Return the view and pass the $products variable to it
        return view('products.index', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
        ]);
        $data = $request->except('image'); // Get all data *except* the image
        // 2. Handle File Upload
        if ($request->hasFile('image')) {
            // Store in 'public/products' folder
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path; // Add the path to our data array
        }
        // 3. Create Product
        Product::create($data);
        // 4. Redirect
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // 'Product $product' is Route-Model Binding.
        // Laravel automatically finds the Product with the ID from the URL.
        return view('products.show', compact('product'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Route-Model Binding automatically fetches the product.
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validate
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $data = $request->except('image');
        // 2. Handle File Upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Store new image
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }
        // 3. Update Product
        $product->update($data);
        // 4. Redirect
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete the image from storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        // Delete the product from the database
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }



    public function shop()
    {
        $products = Product::with('category')->latest()->paginate(12);
        return view('shop.index', compact('products'));
    }
}
