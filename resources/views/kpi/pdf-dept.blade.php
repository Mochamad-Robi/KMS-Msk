<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { color: #7f0d0d; font-size: 18px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #7f0d0d; color: white; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) td { background: #fdf2f2; }
        .header { border-bottom: 2px solid #7f0d0d; padding-bottom: 10px; margin-bottom: 16px; }
        .meta { font-size: 11px; color: #888; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan KPI Departemen — {{ $dept->name }}</h1>
        <p class="meta">Periode: {{ now()->year }} | Dicetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>ID</th>
                <th>Bulan</th>
                <th>Form</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $sub)
                <tr>
                    <td>{{ $sub->user->name }}</td>
                    <td>{{ $sub->user->employee_id }}</td>
                    <td>{{ \Carbon\Carbon::create($sub->period_year, $sub->period_month)->translatedFormat('F') }}</td>
                    <td>{{ $sub->form->title }}</td>
                    <td>{{ ucfirst($sub->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#999;">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>