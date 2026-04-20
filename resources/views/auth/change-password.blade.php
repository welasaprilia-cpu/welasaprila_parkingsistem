@extends('layouts.app')

@section('title', 'Change Password - Parking System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200 p-8 space-y-8">
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl flex items-center justify-center shadow-lg">
                <i class="fas fa-key text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Ganti Password</h1>
            <p class="text-gray-600 text-lg">Masukkan password lama dan password baru untuk mengubah password akun Anda.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500 text-green-700 p-4 rounded-2xl text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('account.password.update') }}" class="space-y-6">
            @csrf

            <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Password Lama</label>
                <input id="current_password" name="current_password" type="password" required
                       class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('current_password') border-red-500 @enderror"
                       placeholder="Masukkan password lama">
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                <input id="password" name="password" type="password" required
                       class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                       placeholder="Masukkan password baru">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="w-full px-4 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                       placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-4 rounded-2xl shadow-xl transition-all">Simpan Password Baru</button>
        </form>

        <div class="text-center pt-4 border-t border-gray-200">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-500 font-medium">← Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection
