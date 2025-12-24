<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {
    }

    public function index(): View
    {
        $items = Portfolio::orderByDesc('created_at')->get();

        return view('admin.portfolio.index', compact('items'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)->get();

        return view('admin.portfolio.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'service_type' => ['required', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $item = new Portfolio();
        $item->title = $data['title'];
        $item->slug = Str::slug($data['title']);
        $item->excerpt = $data['excerpt'];
        $item->description = $data['description'];
        $item->service_type = $data['service_type'];
        $item->client_name = $data['client_name'] ?? null;
        $item->url = $data['url'] ?? null;
        $item->is_featured = $request->boolean('is_featured', true);

        if ($request->hasFile('image')) {
            $item->image_path = $this->imageService->uploadPortfolioImage($request->file('image'));
        }

        $item->save();

        return redirect()->route('admin.portfolio.index')->with('status', 'Projet ajouté au portfolio.');
    }

    public function edit(Portfolio $portfolio): View
    {
        $services = Service::where('is_active', true)->get();

        return view('admin.portfolio.edit', [
            'item' => $portfolio,
            'services' => $services,
        ]);
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'service_type' => ['required', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $portfolio->title = $data['title'];
        $portfolio->slug = Str::slug($data['title']);
        $portfolio->excerpt = $data['excerpt'];
        $portfolio->description = $data['description'];
        $portfolio->service_type = $data['service_type'];
        $portfolio->client_name = $data['client_name'] ?? null;
        $portfolio->url = $data['url'] ?? null;
        $portfolio->is_featured = $request->boolean('is_featured', true);

        if ($request->hasFile('image')) {
            $portfolio->image_path = $this->imageService->uploadPortfolioImage($request->file('image'));
        }

        $portfolio->save();

        return redirect()->route('admin.portfolio.index')->with('status', 'Projet mis à jour.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('status', 'Projet supprimé.');
    }
}