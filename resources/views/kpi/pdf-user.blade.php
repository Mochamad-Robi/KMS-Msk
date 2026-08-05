<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { color: #7f0d0d; font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 13px; color: #555; font-weight: normal; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #7f0d0d; color: white; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) td { background: #fdf2f2; }
        .header { border-bottom: 2px solid #7f0d0d; padding-bottom: 10px; margin-bottom: 16px; }
        .meta { font-size: 11px; color: #888; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan KPI — {{ $targetUser->name }}</h1>
        <h2>{{ $targetUser->employee_id }} | {{ $targetUser->department?->name }} | {{ $targetUser->position?->name }}</h2>
        <p class="meta">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    @forelse($submissions as $sub)
        <h3 style="color:#7f0d0d; margin-top:20px;">
            {{ \Carbon\Carbon::create($sub->period_year, $sub->period_month)->translatedFormat('F Y') }}
            — {{ $sub->form->title }}
        </h3>
        <table>
            <thead>
                <tr>
                    <th style="width:40%">Indikator</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sub->form_data as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p style="color:#999;">Belum ada data KPI.</p>
    @endforelse
</body>
</html>