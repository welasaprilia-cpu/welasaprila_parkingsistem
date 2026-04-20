@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 space-y-8">
        
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white mb-4">Lupa Password?</h1>
            <p class="text-gray-300 text-lg leading-relaxed max-w-sm mx-auto">
                Masukkan email Anda dan kami akan kirimkan link untuk reset password
            </p>
        </div>

        @if (session('status'))
            <div class="bg-green-500/20 border-2 border-green-500/50 text-green-200 p-4 rounded-2xl text-center font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-200 mb-3">
                    <i class="fas fa-envelope mr-2 text-blue-400"></i>
                    Email Address
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>

                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           class="w-full pl-12 pr-4 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-gray-300 focus:ring-4 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300 text-lg font-semibold focus:outline-none @error('email') border-red-400 ring-red-400/30 @enderror"
                           placeholder="admin@parking.com">

                    @error('email')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 via-purple-500 to-emerald-500 text-white font-black py-5 rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-xl">
                Kirim Link Reset Password
            </button>
        </form>

        <!-- BACK LOGIN -->
        <div class="text-center pt-6 border-t border-white/10">
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-semibold text-lg">
                ← Kembali ke Login
            </a>
        </div>

      <div class="text-xs text-gray-400 text-center mt-8 pt-6 border-t border-white/10">
            <p class="opacity-80">
                Demo Login:<br>
                <strong>Apriliya@gmail.com</strong><br>
                <strong>1234567</strong>
            </p>
        </div>

    </div>
</div>
@endsection
        