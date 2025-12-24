@extends('layouts.admin')

@section('title', 'Créer un administrateur')

@section('content')
    <h1>Créer un compte administrateur</h1>

    <form method="POST" action="{{ route('admin.admins.store') }}" class="swbs-form">
        @csrf
        <div class="swbs-form-group">
            <label for="name">Nom</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            @error('email')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="swbs-form-group">
            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" required>
            @error('password')<p class="swbs-form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
    </form>
@endsection