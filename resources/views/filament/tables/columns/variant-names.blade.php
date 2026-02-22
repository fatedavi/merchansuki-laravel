@php
    $record = $getRecord();
    $variants = $record->variants;
@endphp

<div class="flex flex-col min-w-[200px] divide-y divide-gray-100 dark:divide-gray-800">
    @forelse($variants->take(3) as $variant)
        <div class="flex flex-col justify-center p-2 h-[60px] hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors px-3">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">NAME:</span>
            <span class="text-[11px] font-bold text-gray-900 dark:text-white break-words leading-tight" title="{{ $variant->variant_name }}">
                {{ $variant->variant_name }}
            </span>
        </div>
    @empty
        <div class="text-[11px] text-gray-400 italic py-4 text-center">No variants</div>
    @endforelse
</div>
