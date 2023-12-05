<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Instantiate a new ProductController instance.
     */
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:create-product|edit-product|delete-product', ['only' => ['index','show']]);
       $this->middleware('permission:create-product', ['only' => ['create','store']]);
       $this->middleware('permission:edit-product', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-product', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::latest()->paginate(3)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */


     public function store(StoreProductRequest $request): RedirectResponse
    {
        // Validasi form
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'kategori' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Proses gambar yang diunggah
        $imageName = time().'.'.$request->image->extension();

        // Simpan gambar ke penyimpanan Laravel (storage)
        $request->image->storeAs('public/images', $imageName);

        // Simpan produk ke database
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'kategori' => $request->kategori,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
        ]);

        return redirect()->route('products.index')->withSuccess('New product is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
{
    // Validasi form
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'kategori' => 'required|string|max:255',
        'price' => 'required|numeric',
        'stock' => 'required|numeric',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Jika ada file gambar baru diunggah
    if ($request->hasFile('image')) {
        // Hapus gambar lama dari penyimpanan Laravel
        Storage::delete('public/images/' . $product->image);

        // Proses gambar yang diunggah
        $imageName = time().'.'.$request->image->extension();

        // Simpan gambar baru ke penyimpanan Laravel (storage)
        $request->image->storeAs('public/images', $imageName);

        // Update entri di database dengan nama gambar baru
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'kategori' => $request->kategori,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
        ]);
    } else {
        // Jika tidak ada file gambar baru diunggah, update entri di database tanpa menyentuh field gambar
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'kategori' => $request->kategori,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
    }

    return redirect()->back()->withSuccess('Product is updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('products.index')
                ->withSuccess('Product is deleted successfully.');
    }
}
