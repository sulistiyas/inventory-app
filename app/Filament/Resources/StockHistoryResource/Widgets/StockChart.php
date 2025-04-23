<?php

namespace App\Filament\Resources\StockHistoryResource\Widgets;

use App\Models\StockHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Perubahan Stok';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $start = now()->subWeeks(6); // ambil 6 minggu terakhir

        $data = StockHistory::selectRaw("
                DATE_FORMAT(created_at, '%Y-%u') as minggu,
                type,
                SUM(amount) as total
            ")
            ->where('created_at', '>=', $start)
            ->groupBy('minggu', 'type')
            ->orderBy('minggu')
            ->get()
            ->groupBy('type');

        $labels = collect(range(0, 5))->map(function ($i) {
            return now()->subWeeks(5 - $i)->format('Y-W');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Stok Masuk',
                    'data' => $labels->map(fn ($label) =>
                        optional($data['in']->firstWhere('minggu', $label))->total ?? 0
                    ),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
                [
                    'label' => 'Stok Keluar',
                    'data' => $labels->map(fn ($label) =>
                        optional($data['out']->firstWhere('minggu', $label))->total ?? 0
                    ),
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // atau 'bar'
    }
}