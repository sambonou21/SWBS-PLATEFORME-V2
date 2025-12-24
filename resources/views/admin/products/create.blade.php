@extends('layouts.admin')

@section('title', 'Créer un produit')

@section('content')
    <h1>Créer un produit</h1>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="swbs-form">
        @csrf
        <div class="swbs-form-group">
            <label for="name">Nom</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="category_id">Catégorie</label>
            <select id="category_id" name="category_id">
                <option value="">-- Aucune --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="short_description">Accroche</label>
            <input id="short_description" name="short_description" type="text" value="{{ old('short_description') }}" required>
            @error('short_description')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="description">Description détaillée</label>
            <textarea id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
            @error('description')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group swbs-form-group-inline">
            <div>
                <label for="price_fcfa">Prix (FCFA)</label>
                <input id="price_fcfa" name="price_fcfa" type="number" min="0" step="1000" value="{{ old('price_fcfa') }}" required>
                @error('price_fcfa')<p class="swbs-form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="stock">Stock</label>
                <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', 0) }}">
                @error('stock')<p class="swbs-form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    Actif
                </label>
            </div>
        </div>
        <div class="swbs-form-group">
            <label for="image">Image principale</label>
            <input id="image" name="image" type="file" accept="image/*">
            @error('image')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
    </form>
@endsection