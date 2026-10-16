<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7f0d0d">

    {{-- PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/icon-192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MSK Pintar">
    <meta name="mobile-web-app-capable" content="yes">

    <title>{{ config('app.name') }} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon-192.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php use Illuminate\Support\Facades\App; @endphp
<body class="bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-200">

    {{-- ===================== SIDEBAR (desktop only) ===================== --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 text-gray-700 z-50 flex flex-col transition-all duration-300 hidden md:flex">

        {{-- Logo --}}
        <div class="px-3 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-center overflow-hidden">
            <img src="{{ asset('assets/logoapk2.png') }}" alt="Logo PT MSK" class="sidebar-text h-20 w-auto max-w-full object-contain">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo PT MSK" class="sidebar-logo-collapsed hidden h-10 w-10 object-contain">
        </div>

        {{-- ===== LOADING OVERLAY ===== --}}
        <div id="page-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-gray-900 transition-opacity duration-500">
            <div class="mb-6 flex flex-col items-center gap-3">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo PT MSK" class="w-24 h-24 object-contain drop-shadow-2xl"/>
                <p class="text-gray-400 text-xs tracking-[0.3em] uppercase font-semibold">Knowledge Management System</p>
            </div>
            <p class="text-white font-bold text-lg tracking-[0.2em] uppercase mb-4">Loading</p>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-primary-500 animate-bounce" style="animation-delay: 0s"></span>
                <span class="w-3 h-3 rounded-full bg-primary-500 animate-bounce" style="animation-delay: 0.15s"></span>
                <span class="w-3 h-3 rounded-full bg-primary-500 animate-bounce" style="animation-delay: 0.3s"></span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            <ul class="space-y-0.5">

                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('dashboard') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.dashboard') }}</span>
                    </a>
                </li>

                {{-- SECTION: DOKUMEN --}}
                <li class="pt-3">
                    <p class="sidebar-text text-xs text-gray-400 dark:text-gray-500 uppercase px-3 mb-1 font-semibold tracking-wider">Dokumen</p>
                </li>
                <li>
                    <a href="{{ route('documents.policy') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('documents.policy') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.policy') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('documents.roles') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('documents.roles') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.rules') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('documents.knowledge-base') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('documents.knowledge-base') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.knowledge_base') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('documents.explicit-knowledge') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('documents.explicit-knowledge') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8h8M8 12h8M8 16h5"/>
                        </svg>
                        <span class="sidebar-text">Explicit</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('documents.tacit-knowledge') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('documents.tacit-knowledge') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21a8 8 0 0116 0"/>
                        </svg>
                        <span class="sidebar-text">Tacit</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('documents.knowledge-map') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('documents.knowledge-map') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span class="sidebar-text">Knowledge Map</span>
                    </a>
                </li>

                {{-- SECTION: MENU --}}
                <li class="pt-3">
                    <p class="sidebar-text text-xs text-gray-400 dark:text-gray-500 uppercase px-3 mb-1 font-semibold tracking-wider">Menu</p>
                </li>
                @if(Auth::user()->isKadept())
                <li class="hidden">
                    <a href="{{ route('kpi.kadept.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('kpi.kadept.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.kpi') }}</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->isKadept() || Auth::user()->isAdmin() || Auth::user()->isUser() || Auth::user()->isSuperUser())
                <li>
                    <a href="{{ route('kpi.definisi-core-value') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('kpi.definisi-core-value') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.Definition_of_core_value') }}</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('news.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('news.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6 4h2"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.employee_info') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('faq.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('faq.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sidebar-text">FAQ</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookmarks.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('bookmarks.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                        <span class="sidebar-text">Bookmark</span>
                    </a>
                </li>

                {{-- SECTION: ADMIN --}}
                @if(Auth::user()->isAdmin() || Auth::user()->isSuperUser())
                <li class="pt-3">
                    <p class="sidebar-text text-xs text-gray-400 dark:text-gray-500 uppercase px-3 mb-1 font-semibold tracking-wider">Admin</p>
                </li>
                <li>
                    <a href="{{ route('admin.analytics.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.analytics.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="sidebar-text">Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.documents.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.documents.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.manage_documents') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.users.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.user_management') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.news.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.news.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="sidebar-text">{{ __('app.manage_employee_info') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.faqs.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.faqs.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sidebar-text">{{__('app.manage_faq')}}</span>
                    </a>
                </li>
                @php $isMasterActive = request()->routeIs('admin.master.*'); @endphp
                <li>
                    <button onclick="toggleMasterData()"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                                   {{ $isMasterActive ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span class="sidebar-text flex-1 text-left">Master Data</span>
                        <svg id="master-data-arrow" class="sidebar-text w-3.5 h-3.5 transition-transform duration-200 {{ $isMasterActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul id="master-data-submenu" class="mt-0.5 ml-3 space-y-0.5 {{ $isMasterActive ? '' : 'hidden' }}">
                        <li>
                            <a href="{{ route('admin.master.departments.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                      {{ request()->routeIs('admin.master.departments.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                <span class="sidebar-text">Departemen</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.master.positions.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                      {{ request()->routeIs('admin.master.positions.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                <span class="sidebar-text">Jabatan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.master.grades.index') }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                      {{ request()->routeIs('admin.master.grades.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                <span class="sidebar-text">Grade</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('admin.audit-logs.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="sidebar-text">Audit Log</span>
                    </a>
                </li>

                @php $isKpiActive = request()->routeIs('admin.kpi.*'); @endphp
                <li class="hidden">
                    <button onclick="toggleKpiMenu()"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                                {{ $isKpiActive ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="sidebar-text flex-1 text-left">{{ __('app.manage_kpi') }}</span>
                        <svg id="kpi-menu-arrow" class="sidebar-text w-3.5 h-3.5 transition-transform duration-200 {{ $isKpiActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul id="kpi-menu-submenu" class="mt-0.5 ml-3 space-y-0.5 {{ $isKpiActive ? '' : 'hidden' }}">
                        <li>
                            <a href="{{ route('admin.kpi.assignments.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                    {{ request()->routeIs('admin.kpi.assignments.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                <span class="sidebar-text">Rekap &amp; Assignment</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.kpi.rekap.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                    {{ request()->routeIs('admin.kpi.rekap.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                <span class="sidebar-text">Rekap &amp; Download PDF</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.kpi.periods.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                    {{ request()->routeIs('admin.kpi.periods.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                <span class="sidebar-text">Periode KPI</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.kpi.quality-assignments.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                    {{ request()->routeIs('admin.kpi.quality-assignments.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                <span class="sidebar-text">Assignment Kualitatif Cross-Dept</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

            </ul>
        </nav>
    </aside>

    {{-- ===================== MOBILE DRAWER ===================== --}}
    <div id="mobile-drawer-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="closeMobileDrawer()"></div>
    <aside id="mobile-drawer" class="fixed top-0 left-0 h-full w-72 bg-white dark:bg-gray-900 z-50 flex flex-col transform -translate-x-full transition-transform duration-300 md:hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <img src="{{ asset('assets/logosamping.png') }}" alt="Logo PT MSK" class="h-10 w-auto">
            <button onclick="closeMobileDrawer()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- User info --}}
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
            @if(Auth::user()->avatar)
                <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename(Auth::user()->avatar)]) }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-200"/>
            @else
                <div class="w-10 h-10 bg-primary-700 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                </div>
            @endif
            <div class="min-w-0">
                <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->role?->name }} &middot; {{ Auth::user()->department?->name }}</p>
            </div>
        </div>

        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('dashboard') }}" onclick="closeMobileDrawer()"
                       class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('dashboard') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                </li>

                <li class="pt-3"><p class="text-xs text-gray-400 uppercase px-3 mb-1 font-semibold tracking-wider">Dokumen</p></li>
                <li><a href="{{ route('documents.policy') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('documents.policy') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>{{ __('app.policy') }}</a></li>
                <li><a href="{{ route('documents.roles') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('documents.roles') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>{{ __('app.rules') }}</a></li>
                <li><a href="{{ route('documents.knowledge-base') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('documents.knowledge-base') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>{{ __('app.knowledge_base') }}</a></li>
                <li><a href="{{ route('documents.explicit-knowledge') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('documents.explicit-knowledge') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8h8M8 12h8M8 16h5"/></svg>Explicit Knowledge</a></li>
                <li><a href="{{ route('documents.tacit-knowledge') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('documents.tacit-knowledge') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21a8 8 0 0116 0"/></svg>Tacit Knowledge</a></li>
                <li><a href="{{ route('documents.knowledge-map') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('documents.knowledge-map') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>Knowledge Map</a></li>

                <li class="pt-3"><p class="text-xs text-gray-400 uppercase px-3 mb-1 font-semibold tracking-wider">Menu</p></li>
                @if(Auth::user()->isKadept())
                <li class="hidden"><a href="{{ route('kpi.kadept.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('kpi.kadept.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ __('app.kpi') }}</a></li>
                @endif
                @if(Auth::user()->isKadept() || Auth::user()->isAdmin() || Auth::user()->isUser() || Auth::user()->isSuperUser())
                <li><a href="{{ route('kpi.definisi-core-value') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('kpi.definisi-core-value') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>{{ __('app.Definition_of_core_value') }}</a></li>
                @endif
                <li><a href="{{ route('news.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('news.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6 4h2"/></svg>{{ __('app.employee_info') }}</a></li>
                <li><a href="{{ route('faq.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('faq.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>FAQ</a></li>
                <li><a href="{{ route('bookmarks.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('bookmarks.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>Bookmark</a></li>
                <li><a href="{{ route('profile.show') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('profile.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Profil</a></li>

                @if(Auth::user()->isAdmin() || Auth::user()->isSuperUser())
                <li class="pt-3"><p class="text-xs text-gray-400 uppercase px-3 mb-1 font-semibold tracking-wider">Admin</p></li>
                <li><a href="{{ route('admin.analytics.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.analytics.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>Analytics</a></li>
                <li><a href="{{ route('admin.documents.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.documents.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>{{ __('app.manage_documents') }}</a></li>
                <li><a href="{{ route('admin.users.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>{{ __('app.user_management') }}</a></li>
                <li><a href="{{ route('admin.news.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.news.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>{{ __('app.manage_employee_info') }}</a></li>
                <li><a href="{{ route('admin.faqs.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.faqs.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Kelola FAQ</a></li>

                @php $isMasterActive = request()->routeIs('admin.master.*'); @endphp
                <li>
                    <button onclick="toggleMobileMasterData()"
                            class="w-full flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition
                                   {{ $isMasterActive ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span class="flex-1 text-left">Master Data</span>
                        <svg id="mobile-master-data-arrow" class="w-3.5 h-3.5 transition-transform duration-200 {{ $isMasterActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul id="mobile-master-data-submenu" class="mt-0.5 ml-4 space-y-0.5 {{ $isMasterActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('admin.master.departments.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.master.departments.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>Departemen</a></li>
                        <li><a href="{{ route('admin.master.positions.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.master.positions.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>Jabatan</a></li>
                        <li><a href="{{ route('admin.master.grades.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.master.grades.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>Grade</a></li>
                    </ul>
                </li>

                <li><a href="{{ route('admin.audit-logs.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>Audit Log</a></li>

                @php $isKpiActive = request()->routeIs('admin.kpi.*'); @endphp
                <li class="hidden">
                    <button onclick="toggleMobileKpiMenu()"
                            class="w-full flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition
                                {{ $isKpiActive ? 'bg-primary-700 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span class="flex-1 text-left">{{ __('app.manage_kpi') }}</span>
                        <svg id="mobile-kpi-menu-arrow" class="w-3.5 h-3.5 transition-transform duration-200 {{ $isKpiActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul id="mobile-kpi-menu-submenu" class="mt-0.5 ml-4 space-y-0.5 {{ $isKpiActive ? '' : 'hidden' }}">
                        <li><a href="{{ route('admin.kpi.assignments.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kpi.assignments.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>Rekap &amp; Assignment</a></li>
                        <li><a href="{{ route('admin.kpi.rekap.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kpi.rekap.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>Rekap &amp; Download PDF</a></li>
                        <li><a href="{{ route('admin.kpi.periods.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kpi.periods.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>Periode KPI</a></li>
                        <li><a href="{{ route('admin.kpi.quality-assignments.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kpi.quality-assignments.*') ? 'text-primary-700 bg-primary-50 dark:bg-primary-900/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"><span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>Assignment Kualitatif Cross-Dept</a></li>
                    </ul>
                </li>
                @endif

                {{-- Dark mode toggle in drawer --}}
                <li class="pt-3">
                    <button onclick="toggleDarkMode()"
                            class="w-full flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg id="drawer-icon-moon" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg id="drawer-icon-sun" class="w-5 h-5 shrink-0 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                        <span id="drawer-dark-label">Dark Mode</span>
                    </button>
                </li>

                {{-- Logout --}}
                <li>
                    <button type="button" onclick="closeMobileDrawer(); setTimeout(openLogoutModal, 320);"
                            class="w-full flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </li>
            </ul>
        </nav>
    </aside>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div id="main-content" class="md:ml-64 min-h-screen flex flex-col transition-all duration-300">

        {{-- Topbar --}}
        <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between sticky top-0 z-40">

            <div class="flex items-center gap-2.5 md:gap-3 min-w-0">
                <button onclick="toggleSidebar()"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-700 focus:outline-none transition shrink-0">
                    <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Mobile: logo --}}
                <img src="{{ asset('assets/logosamping.png') }}" alt="MSK Pintar"
                     class="md:hidden h-7 w-auto object-contain shrink-0">

                <div class="min-w-0">
                    <h1 class="text-gray-800 dark:text-gray-100 font-semibold text-sm md:text-base truncate">@yield('title')</h1>
                    @hasSection('breadcrumb')
                        <p class="text-gray-400 dark:text-gray-500 text-xs mt-0.5 hidden md:block">@yield('breadcrumb')</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 md:gap-4 shrink-0">

                {{-- Notifikasi --}}
                <div class="relative" id="notif-wrapper">
                    <button onclick="toggleNotif()" class="relative text-gray-500 dark:text-gray-400 hover:text-primary-700 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(Auth::user()->unreadNotificationsCount() > 0)
                            <span class="top-badge">
                                {{ Auth::user()->unreadNotificationsCount() > 9 ? '9+' : Auth::user()->unreadNotificationsCount() }}
                            </span>
                        @endif
                    </button>
                    <div id="notif-dropdown"
                         class="hidden absolute right-0 top-8 w-80 max-w-[calc(100vw-2rem)] bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm">{{ __('app.notifications') }}</p>
                            @if(Auth::user()->unreadNotificationsCount() > 0)
                                <form method="POST" action="{{ route('notifications.readAll') }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-primary-700 hover:underline">{{ __('app.mark_all_read') }}</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700">
                            @forelse(Auth::user()->notifications()->latest()->take(10)->get() as $notif)
                                <a href="{{ route('notifications.read', $notif->id) }}"
                                   class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $notif->is_read ? 'opacity-60' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5
                                            {{ $notif->type === 'birthday' ? 'bg-pink-100 dark:bg-pink-900/30' : ($notif->type === 'document' ? 'bg-blue-100 dark:bg-blue-900/30' : ($notif->type === 'news' ? 'bg-yellow-100 dark:bg-yellow-900/30' : ($notif->type === 'reminder' ? 'bg-orange-100 dark:bg-orange-900/30' : 'bg-primary-50 dark:bg-primary-900/30'))) }}">
                                            <span class="text-xs">{!! $notif->type === 'birthday' ? '&#127874;' : ($notif->type === 'document' ? '&#128196;' : ($notif->type === 'news' ? '&#128240;' : ($notif->type === 'reminder' ? '&#9200;' : '&#128276;'))) !!}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $notif->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if(!$notif->is_read)
                                            <div class="w-2 h-2 bg-primary-700 rounded-full mt-1.5 shrink-0"></div>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">{{ __('app.no_notifications') }}</div>
                            @endforelse
                        </div>
                        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-xs text-primary-700 hover:underline">{{ __('app.see_all_notif') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Profile (desktop only) --}}
                <div class="relative hidden md:block" id="profile-wrapper">
                    <button onclick="toggleProfile()" class="flex items-center gap-2 hover:opacity-80 transition">
                        @if(Auth::user()->avatar)
                            <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename(Auth::user()->avatar)]) }}" class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"/>
                        @else
                            <div class="w-8 h-8 bg-primary-700 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                            </div>
                        @endif
                        <span class="text-sm text-gray-700 dark:text-gray-200 font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="profile-dropdown" class="hidden absolute right-0 top-12 w-52 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm text-gray-700 dark:text-gray-200">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Edit Profile
                        </a>
                        <button type="button" onclick="openLogoutModal()"
                                class="w-full text-left flex items-center gap-3 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-900/20 text-sm text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </div>
                </div>

                {{-- Dark Mode Toggle (desktop only) --}}
                <button onclick="toggleDarkMode()" id="dark-mode-btn"
                        class="hidden md:flex w-8 h-8 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-primary-700 hover:border-primary-300 transition">
                    <svg id="icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg id="icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                </button>

                {{-- Language Switch (desktop only) --}}
                <div class="hidden md:flex items-center gap-1 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden text-xs">
                    <a href="{{ route('language.switch', 'id') }}" class="px-2.5 py-1.5 transition font-medium {{ App::getLocale() === 'id' ? 'bg-primary-700 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">ID</a>
                    <a href="{{ route('language.switch', 'en') }}" class="px-2.5 py-1.5 transition font-medium {{ App::getLocale() === 'en' ? 'bg-primary-700 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">EN</a>
                </div>

            </div>

            <style>
                #sidebar.collapsed nav a,
                #sidebar.collapsed nav button {
                    justify-content: center;
                    padding-left: 0.5rem;
                    padding-right: 0.5rem;
                }
                #sidebar.collapsed nav a.bg-primary-700,
                #sidebar.collapsed nav button.bg-primary-700 {
                    border-radius: 0.75rem;
                    margin: 0 auto;
                    width: 40px;
                }
            </style>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-4 md:p-6 pb-24 md:pb-6">

            {{-- Banner izin notifikasi --}}
            <div id="push-permission-banner"
                 class="hidden bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl px-4 py-3 mb-4 md:mb-6 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 dark:bg-blue-800 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">Aktifkan Notifikasi</p>
                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-0.5">
                        Dapatkan pemberitahuan dokumen baru, berita, dan pengingat langsung di perangkat Anda.
                    </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="document.getElementById('push-permission-banner').remove()"
                            class="text-xs text-blue-500 dark:text-blue-400 hover:underline px-2 py-2">
                        Nanti
                    </button>
                    <button type="button" onclick="enablePushNotification()"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                        Aktifkan
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div data-auto-hide class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg px-4 py-3 mb-4 md:mb-6 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div data-auto-hide class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-4 md:mb-6 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

    {{-- ===================== BOTTOM NAVIGATION (mobile only) ===================== --}}
    <style>
      /* Semua ukuran ditulis manual supaya tidak bergantung build Tailwind */
      .bnav{
        position:fixed; left:0; right:0; bottom:0; z-index:40;
        display:flex; align-items:stretch;
        background:#fff; border-top:1px solid #E5E7EB;
        padding:6px 4px; padding-bottom:calc(6px + env(safe-area-inset-bottom));
      }
      html.dark .bnav{ background:#111827; border-top-color:#374151; }
      @media (min-width:768px){ .bnav{ display:none !important; } }

      .bnav-item{
        flex:1 1 0; min-width:0;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        gap:4px; padding:7px 2px; border-radius:12px;
        text-decoration:none; color:#9CA3AF;
        transition:color .18s ease, background .18s ease;
      }
      .bnav-item.is-active{ color:#7f0d0d; background:#FCF1F1; }
      html.dark .bnav-item{ color:#6B7280; }
      html.dark .bnav-item.is-active{ color:#F87171; background:rgba(127,13,13,.28); }

      .bnav-ico{ position:relative; width:22px; height:22px; flex:0 0 auto; }
      .bnav-ico svg{ width:22px; height:22px; display:block; }

      .bnav-label{
        font-size:10px; line-height:1.15; font-weight:600;
        max-width:100%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
      }

      .bnav-badge{
        position:absolute; top:-5px; right:-8px;
        min-width:15px; height:15px; padding:0 3px; box-sizing:border-box;
        border-radius:999px; background:#EF4444; color:#fff;
        font-size:9px; font-weight:700; line-height:1;
        display:flex; align-items:center; justify-content:center;
        box-shadow:0 0 0 2px #fff;
      }
      html.dark .bnav-badge{ box-shadow:0 0 0 2px #111827; }

      /* Badge di topbar */
      .top-badge{
        position:absolute; top:-5px; right:-7px;
        min-width:16px; height:16px; padding:0 4px; box-sizing:border-box;
        border-radius:999px; background:#EF4444; color:#fff;
        font-size:9.5px; font-weight:700; line-height:1;
        display:flex; align-items:center; justify-content:center;
      }

      /* Ruang supaya konten tidak tertutup bottom nav */
      @media (max-width:767px){
        #main-content > main{ padding-bottom:88px; }
      }
    </style>

    <nav class="bnav">
        @php
            $navItems = [
                ['route' => 'dashboard',           'active' => 'dashboard',       'label' => 'Beranda', 'icon' => 'home'],
                ['route' => 'documents.policy',    'active' => 'documents.*',     'label' => 'Dokumen', 'icon' => 'doc'],
                ['route' => 'news.index',          'active' => 'news.*',          'label' => 'Info',    'icon' => 'news'],
                ['route' => 'notifications.index', 'active' => 'notifications.*', 'label' => 'Notif',   'icon' => 'bell'],
                ['route' => 'profile.show',        'active' => 'profile.*',       'label' => 'Profil',  'icon' => 'user'],
            ];
            $unread = Auth::user()->unreadNotificationsCount();
        @endphp

        @foreach($navItems as $nav)
            @php $isActive = request()->routeIs($nav['active']); @endphp
            <a href="{{ route($nav['route']) }}" class="bnav-item {{ $isActive ? 'is-active' : '' }}">

                <span class="bnav-ico">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ $isActive ? '2.3' : '1.7' }}">
                        @if($nav['icon'] === 'home')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        @elseif($nav['icon'] === 'doc')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        @elseif($nav['icon'] === 'news')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6 4h2"/>
                        @elseif($nav['icon'] === 'bell')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        @endif
                    </svg>

                    @if($nav['icon'] === 'bell' && $unread > 0)
                        <span class="bnav-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                    @endif
                </span>

                <span class="bnav-label">{{ $nav['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- ===================== MODAL KONFIRMASI LOGOUT ===================== --}}
    <style>
      #logout-modal{
        position:fixed; inset:0; z-index:60;
        display:flex; align-items:center; justify-content:center; padding:16px;
        opacity:0; visibility:hidden;
        transition:opacity .28s ease, visibility .28s ease;
      }
      #logout-modal.is-open{ opacity:1; visibility:visible; }

      #logout-scrim{
        position:absolute; inset:0;
        background:rgba(12,8,9,.58);
        backdrop-filter:blur(3px); -webkit-backdrop-filter:blur(3px);
      }

      #logout-box{
        position:relative; width:100%; max-width:368px;
        background:#fff; border-radius:20px; overflow:hidden;
        box-shadow:0 26px 64px -18px rgba(0,0,0,.5);
        transform:translateY(22px) scale(.93);
        transition:transform .38s cubic-bezier(.34,1.4,.5,1);
        padding:28px 24px 22px;
      }
      html.dark #logout-box{ background:#1f2937; }
      #logout-modal.is-open #logout-box{ transform:translateY(0) scale(1); }

      /* ikon + cincin berdenyut */
      #logout-ico{
        position:relative; width:64px; height:64px; border-radius:50%;
        background:#FEE2E2; margin:0 auto 18px;
        display:flex; align-items:center; justify-content:center;
      }
      html.dark #logout-ico{ background:rgba(239,68,68,.18); }
      #logout-ico::after{
        content:''; position:absolute; inset:0; border-radius:50%;
        border:2px solid rgba(239,68,68,.5);
        animation:loPulse 2.1s ease-out infinite;
      }
      @keyframes loPulse{
        0%  { transform:scale(1);    opacity:.75; }
        70% { transform:scale(1.42); opacity:0; }
        100%{ opacity:0; }
      }
      #logout-ico svg{ color:#DC2626; animation:loNudge 2.1s ease-in-out infinite; }
      @keyframes loNudge{
        0%,100%{ transform:translateX(0); }
        50%    { transform:translateX(3px); }
      }

      #logout-title{
        font-size:17px; font-weight:700; text-align:center;
        color:#1F2937; margin-bottom:7px;
      }
      html.dark #logout-title{ color:#F3F4F6; }
      #logout-desc{
        font-size:13.5px; line-height:1.6; text-align:center;
        color:#6B7280; margin-bottom:22px;
      }
      html.dark #logout-desc{ color:#9CA3AF; }
      #logout-desc b{ color:#374151; font-weight:600; }
      html.dark #logout-desc b{ color:#E5E7EB; }

      .lo-row{ display:flex; gap:10px; }
      .lo-btn{
        flex:1; width:100%; box-sizing:border-box;
        padding:12.5px 16px; border-radius:12px;
        font-family:inherit; font-size:14px; font-weight:600;
        cursor:pointer; border:none;
        display:flex; align-items:center; justify-content:center; gap:7px;
        transition:transform .15s ease, box-shadow .22s ease, filter .2s ease, background .2s ease, color .2s ease;
      }
      .lo-cancel{ background:transparent; border:1.5px solid #E5E7EB; color:#6B7280; }
      .lo-cancel:hover{ background:#F9FAFB; color:#374151; }
      html.dark .lo-cancel{ border-color:#4B5563; color:#D1D5DB; }
      html.dark .lo-cancel:hover{ background:#374151; color:#fff; }

      .lo-danger{
        background:linear-gradient(135deg,#EF4444 0%,#C01B1B 55%,#991B1B 100%);
        color:#fff;
        box-shadow:0 9px 22px -9px rgba(185,28,28,.85), inset 0 1px 0 rgba(255,255,255,.18);
      }
      .lo-danger:hover{
        filter:brightness(1.09); transform:translateY(-2px);
        box-shadow:0 14px 28px -10px rgba(185,28,28,.95), inset 0 1px 0 rgba(255,255,255,.22);
      }
      .lo-danger:active{ transform:translateY(0) scale(.98); }

      @media (prefers-reduced-motion:reduce){
        #logout-ico::after, #logout-ico svg{ animation:none; }
        #logout-modal, #logout-box{ transition-duration:.01ms; }
      }
    </style>

    <div id="logout-modal">
        <div id="logout-scrim" onclick="closeLogoutModal()"></div>

        <div id="logout-box">

            <div id="logout-ico">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>

            <p id="logout-title">Keluar dari Akun?</p>
            <p id="logout-desc">
                Anda akan keluar dari <b>{{ Auth::user()->name }}</b><br>
                dan harus masuk kembali untuk mengakses sistem.
            </p>

            <div class="lo-row">
                <button type="button" class="lo-btn lo-cancel" onclick="closeLogoutModal()">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" style="flex:1" id="logout-form">
                    @csrf
                    <button type="submit" class="lo-btn lo-danger" id="logout-confirm">
                        Ya, Keluar
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- ===================== SCRIPTS ===================== --}}
    @stack('scripts')
    <script>

    // ===== PAGE LOADER =====
    const loader = document.getElementById('page-loader');

    window.addEventListener('load', function () {
        setTimeout(function () {
            loader.style.opacity = '0';
            setTimeout(function () {
                loader.style.display = 'none';
            }, 500);
        }, 300);
    });

    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (link && link.href && !link.href.startsWith('#') && !link.href.startsWith('javascript') && link.target !== '_blank' && !link.hasAttribute('onclick')) {
            loader.style.display = 'flex';
            loader.style.opacity = '1';
        }
    });

    document.addEventListener('submit', function () {
        loader.style.display = 'flex';
        loader.style.opacity = '1';
    });

    let sidebarOpen = true;
    const isMobile = () => window.innerWidth < 768;

    function toggleSidebar() {
        if (isMobile()) {
            openMobileDrawer();
        } else {
            const sidebar     = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const texts       = document.querySelectorAll('.sidebar-text');
            const logoCollapsed = document.querySelector('.sidebar-logo-collapsed');
            if (sidebarOpen) {
                sidebar.style.width          = '64px';
                mainContent.style.marginLeft = '64px';
                texts.forEach(el => el.classList.add('hidden'));
                if (logoCollapsed) logoCollapsed.classList.remove('hidden');
                sidebar.classList.add('collapsed');
                sidebarOpen = false;
            } else {
                sidebar.style.width          = '256px';
                mainContent.style.marginLeft = '256px';
                texts.forEach(el => el.classList.remove('hidden'));
                if (logoCollapsed) logoCollapsed.classList.add('hidden');
                sidebar.classList.remove('collapsed');
                sidebarOpen = true;
            }
        }
    }

    function openMobileDrawer() {
        document.getElementById('mobile-drawer').style.transform = 'translateX(0)';
        document.getElementById('mobile-drawer-overlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileDrawer() {
        document.getElementById('mobile-drawer').style.transform = 'translateX(-100%)';
        document.getElementById('mobile-drawer-overlay').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ===== Modal konfirmasi logout =====
    function openLogoutModal() {
        const pd = document.getElementById('profile-dropdown');
        if (pd) pd.classList.add('hidden');

        document.getElementById('logout-modal').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLogoutModal() {
        document.getElementById('logout-modal').classList.remove('is-open');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function (e) {
        const m = document.getElementById('logout-modal');
        if (e.key === 'Escape' && m && m.classList.contains('is-open')) closeLogoutModal();
    });

    // Cegah double-klik saat proses logout
    (function () {
        const lf = document.getElementById('logout-form');
        if (lf) lf.addEventListener('submit', function () {
            const b = document.getElementById('logout-confirm');
            b.disabled = true;
            b.style.opacity = '.7';
        });
    })();

    function toggleMasterData() {
        const submenu = document.getElementById('master-data-submenu');
        const arrow   = document.getElementById('master-data-arrow');
        submenu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function toggleKpiMenu() {
        const submenu = document.getElementById('kpi-menu-submenu');
        const arrow   = document.getElementById('kpi-menu-arrow');
        submenu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function toggleMobileMasterData() {
        const submenu = document.getElementById('mobile-master-data-submenu');
        const arrow   = document.getElementById('mobile-master-data-arrow');
        submenu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function toggleMobileKpiMenu() {
        const submenu = document.getElementById('mobile-kpi-menu-submenu');
        const arrow   = document.getElementById('mobile-kpi-menu-arrow');
        submenu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function toggleNotif() {
        document.getElementById('notif-dropdown').classList.toggle('hidden');
        const pd = document.getElementById('profile-dropdown');
        if (pd) pd.classList.add('hidden');
    }

    function toggleProfile() {
        document.getElementById('profile-dropdown').classList.toggle('hidden');
        document.getElementById('notif-dropdown').classList.add('hidden');
    }

    document.addEventListener('click', function (e) {
        const notifWrapper   = document.getElementById('notif-wrapper');
        const profileWrapper = document.getElementById('profile-wrapper');
        if (notifWrapper   && !notifWrapper.contains(e.target))   document.getElementById('notif-dropdown').classList.add('hidden');
        if (profileWrapper && !profileWrapper.contains(e.target)) { const pd = document.getElementById('profile-dropdown'); if (pd) pd.classList.add('hidden'); }
    });

    document.querySelectorAll('[data-auto-hide]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity    = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    function toggleDarkMode() {
        const html   = document.documentElement;
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
        updateDarkModeIcon(isDark);
    }

    function updateDarkModeIcon(isDark) {
        const im = document.getElementById('icon-moon');
        const is = document.getElementById('icon-sun');
        if (im) im.classList.toggle('hidden', isDark);
        if (is) is.classList.toggle('hidden', !isDark);
        const dm = document.getElementById('drawer-icon-moon');
        const ds = document.getElementById('drawer-icon-sun');
        const dl = document.getElementById('drawer-dark-label');
        if (dm) dm.classList.toggle('hidden', isDark);
        if (ds) ds.classList.toggle('hidden', !isDark);
        if (dl) dl.textContent = isDark ? 'Light Mode' : 'Dark Mode';
    }

    (function () {
        const saved       = localStorage.getItem('darkMode');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark      = saved ? saved === 'dark' : prefersDark;
        if (isDark) document.documentElement.classList.add('dark');
        updateDarkModeIcon(isDark);
    })();

    // ===== PWA Service Worker + Push Notification =====
    const VAPID_PUBLIC_KEY = @json(config('webpush.vapid.public_key'));

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const raw     = window.atob(base64);
        const output  = new Uint8Array(raw.length);
        for (let i = 0; i < raw.length; ++i) output[i] = raw.charCodeAt(i);
        return output;
    }

    async function subscribeToPush(registration) {
        if (!VAPID_PUBLIC_KEY) {
            console.log('VAPID public key belum di-set.');
            return;
        }

        try {
            let subscription = await registration.pushManager.getSubscription();

            if (!subscription) {
                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
                });
            }

            const raw = subscription.toJSON();

            await fetch("{{ route('push.subscribe') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    endpoint: raw.endpoint,
                    keys: raw.keys,
                }),
            });

            console.log('Push subscription tersimpan.');
        } catch (err) {
            console.error('Gagal subscribe push:', err);
        }
    }

    async function enablePushNotification() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            alert('Browser ini tidak mendukung push notification.');
            return;
        }

        const permission = await Notification.requestPermission();

        if (permission === 'granted') {
            const registration = await navigator.serviceWorker.ready;
            await subscribeToPush(registration);
            document.getElementById('push-permission-banner')?.remove();
        } else if (permission === 'denied') {
            alert('Izin notifikasi ditolak. Aktifkan lewat pengaturan browser kalau berubah pikiran.');
            document.getElementById('push-permission-banner')?.remove();
        }
    }

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js')
                .then(async function (registration) {
                    console.log('Service Worker registered:', registration.scope);
                    if (Notification.permission === 'granted') {
                        await subscribeToPush(registration);
                    }
                })
                .catch(function (error) {
                    console.log('Service Worker registration failed:', error);
                });
        });
    }

    // Tampilkan banner izin notifikasi kalau user belum memutuskan
    document.addEventListener('DOMContentLoaded', function () {
        const banner = document.getElementById('push-permission-banner');
        if (!banner) return;

        const supported = ('serviceWorker' in navigator) && ('PushManager' in window) && ('Notification' in window);

        if (supported && Notification.permission === 'default') {
            banner.classList.remove('hidden');
        }
    });
    </script>

</body>
</html>