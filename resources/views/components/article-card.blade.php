@props(['article', 'compact' => false])

<article {{ $attributes->class(['group flex gap-4', 'flex-col' => ! $compact]) }}>
    <a href="{{ route('berita.show', $article['slug']) }}" @class(['block shrink-0', 'w-28 sm:w-32' => $compact])>
        <x-cover
            :article="$article"
            :ratio="$compact ? 'aspect-square' : 'aspect-[16/10]'"
            :show-label="! $compact"
            class="transition duration-300 group-hover:scale-[1.02]"
        />
    </a>

    <div class="flex flex-1 flex-col">
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <a href="{{ route('berita.kategori', $article['category_slug']) }}" class="rounded-full px-2 py-0.5 font-semibold {{ $article['category']['badge'] }}">
                {{ $article['category']['name'] }}
            </a>
            <span class="text-stone-400">{{ $article['published_at']->translatedFormat('d M Y') }}</span>
        </div>

        <h3 @class([
            'mt-2 font-semibold leading-snug text-stone-900 dark:text-white',
            'text-base' => $compact,
            'text-lg' => ! $compact,
        ])>
            <a href="{{ route('berita.show', $article['slug']) }}" class="transition group-hover:text-rose-600 dark:group-hover:text-rose-400">
                {{ $article['title'] }}
            </a>
        </h3>

        @unless ($compact)
            <p class="mt-2 line-clamp-3 text-sm text-stone-500 dark:text-stone-400">{{ $article['excerpt'] }}</p>
            <p class="mt-3 text-xs text-stone-400">{{ $article['author'] }} &middot; {{ $article['reading_time'] }} menit baca</p>
        @endunless
    </div>
</article>
