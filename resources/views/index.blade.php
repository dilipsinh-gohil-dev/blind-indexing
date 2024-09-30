@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Welcome to the Blind Indexing Demo</h1>

    <div class="alert alert-info text-center" role="alert">
        <strong>What is Blind Indexing?</strong>
        Blind indexing is a technique used to protect sensitive data while allowing it to be searchable. This demo showcases how to securely handle and index personal information using encryption methods.
    </div>

    <h2 class="mt-4">Features</h2>
    <ul class="list-group mb-4">
        <li class="list-group-item">Securely encrypt and decrypt user information.</li>
        <li class="list-group-item">Perform search operations without exposing sensitive data.</li>
        <li class="list-group-item">Utilize blind indexing for enhanced privacy protection.</li>
    </ul>

    <h2 class="mt-4">How to Use</h2>
    <p>
        This application allows you to create, view, and search user data while ensuring that sensitive information remains secure. You can navigate to the relevant sections from the menu above.
    </p>

    <h2 class="mt-4">Getting Started</h2>
    <p>
        Click on the links in the navigation bar to begin. If you have any questions, feel free to refer to the documentation or reach out for support.
    </p>

    <div class="text-center">
        <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg mt-4">Go to User Management</a>
    </div>
</div>
@endsection
