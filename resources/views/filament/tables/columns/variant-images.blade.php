@php
    use Illuminate\Support\Facades\Storage;
    $record = $getRecord();
    $variants = $record->variants;
    $primaryImage = $record->images->where('is_primary', true)->first()?->image_path ?? $record->images->first()?->image_path;
@endphp

<div class="flex flex-col min-w-[60px] divide-y divide-gray-100 dark:divide-gray-800">
    @forelse($variants->take(3) as $variant)
        @php
            $image = $variant->image ?? $primaryImage;
            $imageUrl = null;
            if ($image) {
                if (Storage::disk('public')->exists($image)) {
                    $imageUrl = Storage::url($image);
                } elseif (file_exists(public_path('storage/' . $image))) {
                    $imageUrl = asset('storage/' . $image);
                }
            }
            $fallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($variant->variant_name) . '&background=4f46e5&color=fff&size=48';
        @endphp
        
        <div class="flex items-center justify-center p-2 h-[60px] hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
            <div class="flex-shrink-0">
                <img src="{{ $imageUrl ?? $fallbackUrl }}" 
                     class="w-10 h-10 rounded-lg object-cover aspect-square border border-gray-200 shadow-sm" 
                     alt="{{ $variant->variant_name }}"
                     onerror="this.onerror=null;this.src='{{ $fallbackUrl }}';">
            </div>
        </div>
    @empty
        <div class="h-[60px]"></div>
    @endforelse
</div>
