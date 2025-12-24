@extends('layouts.admin')

@section('title', 'Modifier une catégorie')

@section('content')
    <h1>Modifier une catégorie</h1>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="swbs-form">
        @csrf
        @method('PUT')
        <div class="swbs-form-group">
            <label for="name">Nom</label>
            <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required>
            @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="parent_id">Catégorie parente</label>
            <select id="parent_id" name="parent_id">
                <option value="">-- Aucune --</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                @endforeach
            </select>
            @error('parent_id')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
    </form>
@endsection