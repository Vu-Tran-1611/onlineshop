{{-- Subcategory badges to narrow down a broad category request --}}
<div class="flex flex-col gap-3" aria-label="Choose a subcategory">
    @foreach ($categories as $category)
        <div>
            @if ($categories->count() > 1)
                <p class="mb-1.5 text-sm font-semibold text-slate-600">{{ $category->name }}</p>
            @endif
            <div class="flex flex-wrap gap-2">
                @foreach ($category->subCategories as $subCategory)
                    <button type="button" data-bot-badge
                        data-category-id="{{ $category->id }}"
                        data-sub-category-id="{{ $subCategory->id }}"
                        class="rounded-full border border-sky-600 bg-white px-3 py-1 text-sm font-medium text-sky-700 transition-colors duration-200 hover:bg-sky-600 hover:text-white disabled:cursor-not-allowed disabled:opacity-60">
                        {{ $subCategory->name }}
                    </button>
                @endforeach
                <button type="button" data-bot-badge
                    data-category-id="{{ $category->id }}"
                    class="rounded-full border border-slate-300 bg-white px-3 py-1 text-sm font-medium text-slate-700 transition-colors duration-200 hover:border-slate-500 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-60">
                    All {{ $category->name }}
                </button>
            </div>
        </div>
    @endforeach
</div>
