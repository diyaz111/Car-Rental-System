@extends('layouts.app-login')

@section('content')
<h2>Login</h2>

<!-- Display error message -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<!-- Login Form -->
<form method="POST" action="{{ url('login') }}">
    @csrf
    <label for="email">Email:</label>
    <input type="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>
</div>
            </div>
        </div>
    </div>
</div>
