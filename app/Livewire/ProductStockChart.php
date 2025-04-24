<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockHistory;

class ProductStockChart extends Component
{
    public $selectedProduct = null;
    public $selectedCategory = null;

    public function render()
    {
        $categories = Category::all();
        $products = Product::query()
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->get();

        $chartData = $this->generateChartData();

        return view('livewire.product-stock-chart', compact('categories', 'products', 'chartData'));
    }

    protected function generateChartData()
    {
        if (!$this->selectedProduct) return null;

        $history = StockHistory::where('product_id', $this->selectedProduct)
            ->whereDate('created_at', '>=', now()->subDays(14))
            ->orderBy('created_at')
            ->get();

        $labels = collect(range(0, 13))->map(fn($i) => now()->subDays(13 - $i)->format('Y-m-d'));

        $masuk = $labels->map(function ($date) use ($history) {
            return $history->where('type', 'in')->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])->sum('amount');
        });

        $keluar = $labels->map(function ($date) use ($history) {
            return $history->where('type', 'out')->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])->sum('amount');
        });

        return [
            'labels' => $labels,
            'masuk' => $masuk,
            'keluar' => $keluar,
        ];
    }
}

