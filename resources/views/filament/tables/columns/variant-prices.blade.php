@php
    $record = $getRecord();
    $variants = $record->variants;
@endphp

<div class="flex flex-col min-w-[150px] divide-y divide-gray-100 dark:divide-gray-800">
    @forelse($variants->take(3) as $variant)
        <div class="flex flex-col justify-center p-2 h-[60px] hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">PRICE:</span>
            <span class="text-[12px] font-extrabold text-indigo-600 dark:text-indigo-400">
                Rp{{ number_format($variant->price, 0, ',', '.') }}
            </span>
        </div>
    @empty
        <div class="h-[60px]"></div>
    @endforelse
</div>
