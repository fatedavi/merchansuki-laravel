@php
    use Illuminate\Support\Facades\Storage;
    
    $variants = $getRecord()->variants;
    $primaryImage = $getRecord()->images->where('is_primary', true)->first()?->image_path 
        ?? $getRecord()->images->first()?->image_path;
@endphp

<div class="flex flex-col max-w-lg divide-y divide-gray-100 dark:divide-gray-800">
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
            
            $fallbackUrl = 'https://ui-avatars.com/api/?name=' 
                . urlencode($variant->variant_name) 
                . '&background=4f46e5&color=fff&size=48';
        @endphp
        
        <div class="flex gap-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors first:rounded-t-lg last:rounded-b-lg">
            
            <!-- Image Variant -->
            <div class="flex-shrink-0">
                <img src="{{ $imageUrl ?? $fallbackUrl }}" 
                     class="w-14 h-14 rounded-lg object-cover aspect-square border border-gray-200 shadow-sm" 
                     alt="{{ $variant->variant_name }}"
                     onerror="this.onerror=null;this.src='{{ $fallbackUrl }}';">
            </div>
            
            <!-- Info Variant -->
            <div class="flex-grow flex flex-col justify-center gap-1.5 min-w-0">
                
                <!-- NAME + STATUS -->
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-start gap-1.5 flex-1 min-w-0">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight shrink-0 mt-0.5">
                            NAME:
                        </span>
                        <span class="text-[12px] font-bold text-gray-900 dark:text-white break-words leading-tight">
                            {{ $variant->variant_name }}
                        </span>
                    </div>

                    <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase shrink-0
                        {{ $variant->status === 'active' 
                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' 
                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                        {{ $variant->status === 'active' ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <!-- PRICE & STOCK -->
                <div class="flex items-center gap-6">
                    
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">
                            PRICE:
                        </span>
                        <span class="text-[12px] font-extrabold text-indigo-600 dark:text-indigo-400">
                            Rp{{ number_format($variant->price, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">
                            STOCK:
                        </span>
                        <span class="text-[12px] 
                            {{ $variant->stock <= 5 
                                ? 'text-red-600 font-extrabold' 
                                : 'text-gray-700 dark:text-gray-300 font-bold' }}">
                            {{ $variant->stock }}
                        </span>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="text-[11px] text-gray-400 italic py-4 text-center">
            No variants available
        </div>
    @endforelse
    
    @if($variants->count() > 3)
        <div class="text-[10px] font-bold text-gray-500 text-center bg-gray-50/80 dark:bg-gray-800/80 py-2 border-t border-gray-100 dark:border-gray-800 rounded-b-lg">
            +{{ $variants->count() - 3 }} MORE VARIANTS
        </div>
    @endif
</div>