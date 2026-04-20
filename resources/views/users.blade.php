@extends('layouts.app')

@section('title', 'Users - Parking System')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-5xl font-black mb-2">User Management</h1>
            <p class="text-2xl text-gray-400">Manage admins and customers</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn px-12 py-6 text-xl shadow-2xl inline-flex items-center gap-3">
            <i class="fas fa-plus"></i>
            Add User
        </a>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="text-left py-6 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">User</th>
                        <th class="text-left py-6 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Email</th>
                        <th class="text-left py-6 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Role</th>
                        <th class="text-left py-6 pr-8 font-bold text-xl uppercase tracking-wider text-gray-300">Parking Spot</th>
                        <th class="text-left py-6 font-bold text-xl uppercase tracking-wider text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
@foreach($users as $user)
                    <tr class="hover:bg-white/5 transition-all group">
                        <td class="py-8 pr-8">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-2xl font-bold text-white shadow-xl">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-xl text-white">{{ $user->name }}</p>
                                    <p class="text-gray-400 text-sm">ID: #{{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-8 pr-8">
                            <p class="font-mono text-lg text-gray-200 break-all">{{ $user->email }}</p>
                        </td>
                        <td class="py-8 pr-8">
                            <span class="status-badge {{ $user->role == 'admin' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white' : 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-8 pr-8">
                            @if($user->parkingSpot)
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 border border-emerald-500/50 rounded-2xl text-emerald-300 font-semibold">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $user->parkingSpot->location }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500/20 border border-gray-500/50 rounded-2xl text-gray-400 font-semibold">
                                    <i class="fas fa-minus"></i>
                                    No spot
                                </span>
                            @endif
                        </td>
                        <td class="py-8">
                            <div class="flex gap-3">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-emerald px-6 py-3 text-sm shadow-lg">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 px-6 py-3 text-sm rounded-2xl font-semibold text-white shadow-lg transition-all">
                                        <i class="fas fa-trash mr-2"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
{{ $users->links() }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card p-12 text-center">
            <i class="fas fa-user-shield text-6xl text-orange-400 mb-6"></i>
            <p class="text-6xl font-black">{{ App\Models\User::where('role', 'admin')->count() }}</p>
            <p class="text-2xl font-bold text-gray-400 mt-4">Administrators</p>
        </div>
        <div class="card p-12 text-center">
            <i class="fas fa-user text-6xl text-blue-400 mb-6"></i>
            <p class="text-6xl font-black">{{ App\Models\User::where('role', 'user')->count() }}</p>
            <p class="text-2xl font-bold text-gray-400 mt-4">Customers</p>
        </div>
        <div class="card p-12 text-center">
            <i class="fas fa-users text-6xl text-emerald-400 mb-6"></i>
            <p class="text-6xl font-black">{{ App\Models\User::count() }}</p>
            <p class="text-2xl font-bold text-gray-400 mt-4">Total Users</p>
        </div>
    </div>
</div>
@endsection

