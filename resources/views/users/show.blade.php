@extends('layouts.app')

@section('content')
<style>
    .list-group-item {
        word-wrap: break-word; /* Allows long words to break and wrap onto the next line */
        white-space: normal;   /* Allows text to wrap normally */
    }
</style>

<div class="container">
    <h1 class="text-center mb-4">Data Comparison</h1>

    <div class="row">
        <!-- Left Column: User Inputted Data -->
        <div class="col-md-6">
            <h2 class="mb-3">User Inputted Data</h2>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                <li class="list-group-item"><strong>SSN:</strong> {{ $user->ssn }}</li>
                <li class="list-group-item"><strong>Phone:</strong> {{ $user->phone }}</li>
                <li class="list-group-item"><strong>Address:</strong> {{ $user->address }}</li>
                <!-- Add other fields as necessary -->
            </ul>
        </div>

        <!-- Right Column: Data In Database -->
        <div class="col-md-6">
            <h2 class="mb-3">Data In Database</h2>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Name:</strong> {{ $user->encrypted_name }}</li>
                <li class="list-group-item"><strong>Name Index:</strong> {{ $user->name_index }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->encrypted_email }}</li>
                <li class="list-group-item"><strong>Email Index:</strong> {{ $user->email_index }}</li>
                <li class="list-group-item"><strong>SSN:</strong> {{ $user->encrypted_ssn }}</li>
                <li class="list-group-item"><strong>SSN Index:</strong> {{ $user->ssn_index }}</li>
                <li class="list-group-item"><strong>Phone:</strong> {{ $user->phone }}</li>
                <li class="list-group-item"><strong>Address:</strong> {{ $user->address }}</li>
                <!-- Add other fields as necessary -->
            </ul>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to User List</a>
    </div>
</div>
@endsection
