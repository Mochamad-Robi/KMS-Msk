@extends('layouts.app')
@section('title', 'KPI')

@section('content')


    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">KPI Saya</h2>
            <p class="text-gray-400 text-sm mt-0.5">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        <a href="{{ route('kpi.download.user') }}"
           class="border border-primary-700 text-primary-700 hover:bg-primary-50 text-sm font-semibold px-4 py-2 rounded-lg transition">
            ↓ Download PDF
        </a>
    </div>

    {{-- Form Input KPI --}}
    @if($form)
        @php
            $alreadySubmitted = $submissions->where('period_month', now()->month)
                                            ->where('period_year', now()->year)
                                            ->isNotEmpty();
        @endphp

        @if($alreadySubmitted)
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
                ✓ Anda sudah mengisi KPI untuk bulan {{ now()->translatedFormat('F Y') }}.
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6 max-w-2xl">
                <h3 class="font-semibold text-gray-800 mb-4">{{ $form->title }}</h3>

                <form method="POST" action="{{ route('kpi.store') }}">
                    @csrf
                    <input type="hidden" name="kpi_form_id" value="{{ $form->id }}"/>

                    <div class="space-y-4">
                        @foreach($form->form_schema as $field)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $field['label'] }}
                                    @if(!empty($field['required']))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if($field['type'] === 'text')
                                    <input type="text" name="form_data[{{ $field['key'] }}]"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                           {{ !empty($field['required']) ? 'required' : '' }}/>

                                @elseif($field['type'] === 'number')
                                    <input type="number" name="form_data[{{ $field['key'] }}]"
                                           min="{{ $field['min'] ?? 0 }}" max="{{ $field['max'] ?? 100 }}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                           {{ !empty($field['required']) ? 'required' : '' }}/>

                                @elseif($field['type'] === 'textarea')
                                    <textarea name="form_data[{{ $field['key'] }}]" rows="3"
                                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                              {{ !empty($field['required']) ? 'required' : '' }}></textarea>

                                @elseif($field['type'] === 'select')
                                    <select name="form_data[{{ $field['key'] }}]"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                        <option value="">-- Pilih --</option>
                                        @foreach($field['options'] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <button type="submit"
                            class="mt-6 bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                        Simpan KPI
                    </button>
                </form>
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl border border-gray-100 p-10 text-center mb-6">
            <p class="text-gray-400 text-sm">Belum ada form KPI yang tersedia untuk jabatan Anda.</p>
        </div>
    @endif

    {{-- Riwayat Submission --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Riwayat KPI</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($submissions as $sub)
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">
                            {{ \Carbon\Carbon::create($sub->period_year, $sub->period_month)->translatedFormat('F Y') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $sub->form->title }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $sub->status === 'approved' ? 'bg-green-50 text-green-600' :
                           ($sub->status === 'submitted' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-400') }}">
                        {{ ucfirst($sub->status) }}
                    </span>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">
                    Belum ada riwayat KPI
                </div>
            @endforelse
        </div>
    </div>

@endsection