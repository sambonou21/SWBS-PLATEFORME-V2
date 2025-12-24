<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(
        protected ImageService $imageService
    ) {
    }

    public function index(): View
    {
        $services = Service::orderBy('title')->get();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'base_price_fcfa' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $service = new Service();
        $service->title = $data['title'];
        $service->slug = Str::slug($data['title']);
        $service->short_description = $data['short_description'];
        $service->description = $data['description'];
        $service->base_price_fcfa = $data['base_price_fcfa'] ?? null;
        $service->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $service->image_path = $this->imageService->uploadServiceImage($request->file('image'));
        }

        $service->save();

        return redirect()->route('admin.services.index')->with('status', 'Service créé avec succès.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'base_price_fcfa' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $service->title = $data['title'];
        $service->slug = Str::slug($data['title']);
        $service->short_description = $data['short_description'];
        $service->description = $data['description'];
        $service->base_price_fcfa = $data['base_price_fcfa'] ?? null;
        $service->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $service->image_path = $this->imageService->uploadServiceImage($request->file('image'));
        }

        $service->save();

        return redirect()->route('admin.services.index')->with('status', 'Service mis à jour.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service supprimé.');
    }
}