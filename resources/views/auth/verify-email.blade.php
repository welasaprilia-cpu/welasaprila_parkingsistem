@extends('layouts.app')

@section('title', 'Verify Email Address')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-8 bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Thanks for signing up!
            </h2>
            <p class="text-lg text-gray-600 mb-8">
                Before getting started, could you verify your email address by clicking on the link we just emailed to you?
                If you didn't receive the email, we will gladly send you another.
            </p>
            
            @if (session('status') == 'verification-link-sent')
                <p class="text-sm font-medium text-green-600 mb-6">
                    A new verification link has been sent to your email address.
                </p>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-xl font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-green-500">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-xl font-semibold transition-colors">
                    Log Out
                </button>
            </form>
        </div>

        <div class="text-sm text-center text-gray-600">
            Already verified? 
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Click here to login
            </a>
        </div>
    </div>
</div>
@endsection

