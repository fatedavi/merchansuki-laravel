@php
    $record = $getRecord();
    $variants = $record->variants;
@endphp

<div class="flex flex-col min-w-[100px] divide-y divide-gray-100 dark:divide-gray-800">
    @forelse($variants->take(3) as $variant)
        <div class="flex flex-col justify-center p-2 h-[60px] hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">STOCK:</span>
            <span class="text-[12px] {{ $variant->stock <= 5 ? 'text-red-600 font-extrabold' : 'text-gray-700 dark:text-gray-300 font-bold' }}">
                {{ $variant->stock }}
            </span>
        </div>
    @empty
        <div class="h-[60px]"></div>
    @endforelse
</div>
