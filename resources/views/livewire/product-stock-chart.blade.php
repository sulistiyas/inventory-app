<div>
    <div class="flex gap-2 mb-4">
        <select wire:model="selectedCategory" class="border p-1 rounded">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <select wire:model="selectedProduct" class="border p-1 rounded">
            <option value="">Pilih Produk</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    @if($chartData)
        <canvas id="stockChart"></canvas>

        <script>
            const ctx = document.getElementById('stockChart');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [
                        {
                            label: 'Masuk',
                            data: {!! json_encode($chartData['masuk']) !!},
                            borderColor: 'green',
                            backgroundColor: 'rgba(16,185,129,0.1)',
                            tension: 0.3,
                        },
                        {
                            label: 'Keluar',
                            data: {!! json_encode($chartData['keluar']) !!},
                            borderColor: 'red',
                            backgroundColor: 'rgba(239,68,68,0.1)',
                            tension: 0.3,
                        }
                    ]
                }
            });
        </script>
    @else
        <p class="text-gray-500">Pilih produk untuk melihat grafik stok harian.</p>
    @endif
</div>
