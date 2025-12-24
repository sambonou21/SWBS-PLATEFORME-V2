@extends('layouts.admin')

@section('title', 'Modifier un service')

@section('content')
    <h1>Modifier un service</h1>

    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="swbs-form">
        @csrf
        @method('PUT')
        <div class="swbs-form-group">
            <label for="title">Titre</label>
            <input id="title" name="title" type="text" value="{{ old('title', $service->title) }}" required>
            @error('title')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="short_description">Accroche</label>
            <input id="short_description" name="short_description" type="text" value="{{ old('short_description', $service->short_description) }}" required>
            @error('short_description')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="description">Description détaillée</label>
            <textarea id="description" name="description" rows="6" required>{{ old('description', $service->description) }}</textarea>
            @error('description')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group swbs-form-group-inline">
            <div>
                <label for="base_price_fcfa">Prix indicatif (FCFA)</label>
                <input id="base_price_fcfa" name="base_price_fcfa" type="number" min="0" step="1000" value="{{ old('base_price_fcfa', $service->base_price_fcfa) }}">
                @error('base_price_fcfa')<p class="swbs-form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                    Actif
                </label>
            </div>
        </div>
        <div class="swbs-form-group">
            <label for="image">Image (optionnelle)</label>
            <input id="image" name="image" type="file" accept="image/*">
            @if($service->image_path)
                <p>Image actuelle : <a href="{{ $service->image_path }}" target="_blank">voir</a></p>
            @endif
            @error('image')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
    </form>
@endsection