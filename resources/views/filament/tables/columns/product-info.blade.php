@php
    $record = $getRecord();
@endphp

<div class="flex flex-col gap-0.5 py-1.5 px-1 min-w-[200px]">
    <!-- Nama Product dan Info -->
    <div class="flex items-center gap-1">
        <span class="font-medium text-gray-900 dark:text-white text-xs truncate" title="{{ $record->name }}">
            {{ $record->name }}
        </span>
        @if($record->is_featured)
            <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-yellow-100 text-yellow-600 shrink-0" title="Featured">
                <svg class="w-2 h-2 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </span>
        @endif
    </div>
    
    @if($record->category)
        <div class="text-[10px] text-gray-500 dark:text-gray-400 truncate leading-tight mt-0.5 font-medium">
            Category: {{ $record->category->name }}
        </div>
    @endif
    
    @if($record->variants->count() > 0)
        <div class="text-[9px] text-gray-400 mt-1 flex gap-2">
            <span>{{ $record->variants->count() }} Variants</span>
            <span>•</span>
            <span>{{ $record->variants->sum('stock') }} Total Stock</span>
        </div>
    @endif
</div>
