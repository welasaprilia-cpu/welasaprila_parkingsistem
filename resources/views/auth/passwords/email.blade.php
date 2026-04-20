@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Forgot your password?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                If you forgot your password, we'll send you an email with a link to reset it.
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm @error('email') border-red-500 @enderror" 
                           placeholder="Email address">
                    @error('email')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end">
                <a class="text-sm text-blue-600 hover:text-blue-500" href="{{ route('login') }}">
                    Back to login
                </a>
            </div>

            <div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-xl font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-green-500">
                    Send Password Reset Link
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

