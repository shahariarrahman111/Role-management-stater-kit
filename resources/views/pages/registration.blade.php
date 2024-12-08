@extends('layouts.app')

@section('content')

    <form action="{{ route('user.register') }}" method="post">
    @csrf
    <input type="text" name="name" placeholder="Enter your name">
    <input type="email" name="email" placeholder="Enter your email">
    <input type="text" name="phone" placeholder="Enter your phone">
    <input type="password" name="password" placeholder="Enter your password">
    <input type="password" name="password_confirmation" placeholder="Confirm your password">
    <button type="submit">Register</button>

    </form>


@endsection