@php
    use Illuminate\Support\Facades\Storage;
    $record = $getRecord();
    $images = $record->images;
@endphp

<div class="flex flex-wrap gap-2 py-2">
    @forelse($images->take(3) as $image)
        @php
            $imageUrl = null;
            if ($image->image_path) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    $imageUrl = Storage::url($image->image_path);
                } elseif (file_exists(public_path('storage/' . $image->image_path))) {
                    $imageUrl = asset('storage/' . $image->image_path);
                }
            }
        @endphp
        
        @if($imageUrl)
            <img src="{{ $imageUrl }}" 
                 class="w-16 h-16 rounded-lg object-cover aspect-square border border-gray-200 shadow-sm" 
                 alt="{{ $record->name }}">
        @endif
    @empty
        @php
            $fallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=f3f4f6&color=a1a1aa&size=128';
        @endphp
        <img src="{{ $fallbackUrl }}" 
             class="w-16 h-16 rounded-lg object-cover aspect-square border border-gray-200 shadow-sm" 
             alt="{{ $record->name }}">
    @endforelse

    @if($images->count() > 3)
        <div class="w-16 h-16 rounded-lg bg-gray-50 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-gray-400">
            <span class="text-[10px] font-bold">+{{ $images->count() - 3 }}</span>
            <span class="text-[8px] uppercase font-medium">More</span>
        </div>
    @endif
</div>
