<!-- resources/views/users/form.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($user) ? 'Edit User' : 'Create User' }}</h2>

    <!-- Display Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT') <!-- Include this for update requests -->
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', isset($user) ? $user->phone : '') }}" required>
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', isset($user) ? $user->address : '') }}" required>
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ssn" class="form-label">SSN</label>
            <input type="text" class="form-control" id="ssn" name="ssn" value="{{ old('ssn', isset($user) ? $user->ssn : '') }}" required>
            @error('ssn')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="form-text text-muted">Leave blank to keep current password.</small>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Submit' }}</button>
    </form>
</div>
@endsection
