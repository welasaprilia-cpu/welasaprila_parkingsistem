@extends('layouts.app')

@section('title', 'Add User - Parking System')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-10">
        <div class="mb-10">
            <h1 class="text-4xl font-black mb-2">Add New User</h1>
            <p class="text-gray-500">Buat user baru untuk sistem parkir.</p>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('password')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mt-10">
                <a href="{{ route('users.index') }}" class="flex-1 text-center py-4 px-8 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition">
                    Cancel
                </a>
                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white py-4 px-8 rounded-xl font-bold text-lg shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
