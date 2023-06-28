<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::orderBy('created_at', 'DESC')->get();
        $product = Product::cursorpaginate(5);
        return view('product.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     Product::create($request->all());
 
    //     return redirect()->route('product.index')->with('success', 'Product added successfully');
    // }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'product_code' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Simpan gambar
        $imagePath = $request->file('image')->store('images/products', 'public');

        // Buat produk baru
        $product = new Product();
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->product_code = $request->input('product-code');
        $product->description = $request->input('description');
        $product->image = $imagePath;
        $product->save();

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('product.index')->with('success', 'Product created successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $product = Product::where('title', 'LIKE', "%$search%")
                    ->orWhere('price', 'LIKE', "%$search%")
                    ->orWhere('product-code', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%")
                    ->get();

        return view('product.index', compact('product'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());// Validasi input
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'product_code' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Dapatkan produk yang akan diperbarui
        $product = Product::findOrFail($id);

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar yang lama
            // Storage::disk('public')->delete($product->image);

            // Simpan gambar yang baru
            $imagePath = $request->file('image')->store('images/products', 'public');
            $product->image = $imagePath;
        }

        // Perbarui detail produk
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->product_code = $request->input('product_code');
        $product->description = $request->input('description');
        $product->save();

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
 
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $product = Product::findOrFail($id);
 
    //     $product->update($request->all());
 
    //     return redirect()->route('product.index')->with('success', 'product updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
 
        $product->delete();
 
        return redirect()->route('product.index')->with('success', 'product deleted successfully');
    }
}
