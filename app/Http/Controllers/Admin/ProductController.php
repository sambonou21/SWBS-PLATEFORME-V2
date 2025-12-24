<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageService;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ImageService $imageService,
        protected CurrencyService $currencyService
    ) {
    }

    public function index(): View
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price_fcfa' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $product = new Product();
        $product->name = $data['name'];
        $product->slug = Str::slug($data['name']);
        $product->category_id = $data['category_id'] ?? null;
        $product->short_description = $data['short_description'];
        $product->description = $data['description'];
        $product->price_fcfa = $data['price_fcfa'];
        $product->is_active = $request->boolean('is_active', true);
        $product->stock = $data['stock'] ?? 0;

        if ($request->hasFile('image')) {
            $product->main_image_path = $this->imageService->uploadProductImage($request->file('image'));
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('status', 'Produit créé.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price_fcfa' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $product->name = $data['name'];
        $product->slug = Str::slug($data['name']);
        $product->category_id = $data['category_id'] ?? null;
        $product->short_description = $data['short_description'];
        $product->description = $data['description'];
        $product->price_fcfa = $data['price_fcfa'];
        $product->is_active = $request->boolean('is_active', true);
        $product->stock = $data['stock'] ?? 0;

        if ($request->hasFile('image')) {
            $product->main_image_path = $this->imageService->uploadProductImage($request->file('image'));
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('status', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Produit supprimé.');
    }
}