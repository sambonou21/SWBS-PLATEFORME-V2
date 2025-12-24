@extends('layouts.admin')

@section('title', 'Modifier un projet')

@section('content')
    <h1>Modifier un projet</h1>

    <form method="POST" action="{{ route('admin.portfolio.update', $item) }}" enctype="multipart/form-data" class="swbs-form">
        @csrf
        @method('PUT')
        <div class="swbs-form-group">
            <label for="title">Titre</label>
            <input id="title" name="title" type="text" value="{{ old('title', $item->title) }}" required>
            @error('title')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="excerpt">Résumé court</label>
            <input id="excerpt" name="excerpt" type="text" value="{{ old('excerpt', $item->excerpt) }}" required>
            @error('excerpt')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="description">Description détaillée</label>
            <textarea id="description" name="description" rows="6" required>{{ old('description', $item->description) }}</textarea>
            @error('description')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="service_type">Type de service</label>
            <select id="service_type" name="service_type" required>
                @foreach($services as $service)
                    @php $slug = \Illuminate\Support\Str::slug($service->title); @endphp
                    <option value="{{ $slug }}" @selected(old('service_type', $item->service_type) === $slug)>{{ $service->title }}</option>
                @endforeach
            </select>
            @error('service_type')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="client_name">Client</label>
            <input id="client_name" name="client_name" type="text" value="{{ old('client_name', $item->client_name) }}">
            @error('client_name')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="url">Lien public (optionnel)</label>
            <input id="url" name="url" type="url" value="{{ old('url', $item->url) }}">
            @error('url')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group swbs-form-group-inline">
            <div>
                <label>
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                    Mettre en avant
                </label>
            </div>
        </div>
        <div class="swbs-form-group">
            <label for="image">Image (optionnelle)</label>
            <input id="image" name="image" type="file" accept="image/*">
            @if($item->image_path)
                <p>Image actuelle : <a href="{{ $item->image_path }}" target="_blank">voir</a></p>
            @endif
            @error('image')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
    </form>
@endsection