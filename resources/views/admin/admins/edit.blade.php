@extends('layouts.admin')

@section('title', 'Modifier un administrateur')

@section('content')
    <h1>Modifier un administrateur</h1>

    <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="swbs-form">
        @csrf
        @method('PUT')
        <div class="swbs-form-group">
            <label for="name">Nom</label>
            <input id="name" name="name" type="text" value="{{ old('name', $admin->name) }}" required>
            @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input id="password" name="password" type="password">
            @error('password')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
    </form>
@endsection