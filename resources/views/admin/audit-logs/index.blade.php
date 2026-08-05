@extends('layouts.app')
@section('title', 'Audit Log')

@section('content')

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800">Audit Log</h2>
        <p class="text-gray-400 text-sm mt-0.5">Rekam aktivitas semua user di dalam sistem</p>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                    <select name="user_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Module</label>
                    <select name="module"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">Semua Module</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                                {{ ucfirst($module) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 bg-primary-700 hover:bg-primary-800 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg transition text-sm font-semibold">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Waktu</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">User</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Aksi</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Module</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Deskripsi</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-5 py-3">
                            @if($log->user)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-primary-700 rounded-full flex items-center justify-center shrink-0">
                                        <span class="text-white text-xs font-bold">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-xs">{{ $log->user->name }}</p>
                                        <p class="text-gray-400 text-xs">{{ $log->user->employee_id }}</p>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">System</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $actionColors = [
                                    'login'           => 'bg-green-50 text-green-600',
                                    'logout'          => 'bg-gray-100 text-gray-500',
                                    'view_document'   => 'bg-blue-50 text-blue-600',
                                    'upload_document' => 'bg-purple-50 text-purple-600',
                                    'view_news'       => 'bg-yellow-50 text-yellow-600',
                                    'delete'          => 'bg-red-50 text-red-600',
                                ];
                                $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-500';
                            @endphp
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $color }}">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs capitalize">{{ $log->module }}</td>
                        <td class="px-5 py-3 text-gray-600 text-xs max-w-xs truncate">{{ $log->description }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs font-mono">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada log aktivitas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>

@endsection