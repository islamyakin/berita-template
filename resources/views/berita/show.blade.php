<x-layout :title="$article['title']" :description="$article['excerpt']">
    <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs text-stone-400" aria-label="Breadcrumb">
        <a href="{{ route('berita.index') }}" class="hover:text-rose-600 dark:hover:text-rose-400">Beranda</a>
        <span>/</span>
        <a href="{{ route('berita.kategori', $article['category_slug']) }}" class="hover:text-rose-600 dark:hover:text-rose-400">{{ $article['category']['name'] }}</a>
        <span>/</span>
        <span class="truncate text-stone-500 dark:text-stone-400">{{ \Illuminate\Support\Str::limit($article['title'], 40) }}</span>
    </nav>

    <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <article>
            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $article['category']['badge'] }}">{{ $article['category']['name'] }}</span>

            <h1 class="mt-4 text-3xl font-bold leading-tight text-stone-900 dark:text-white sm:text-4xl">{{ $article['title'] }}</h1>

            <p class="mt-4 text-lg text-stone-600 dark:text-stone-400">{{ $article['excerpt'] }}</p>

            <div class="mt-6 flex flex-wrap items-center gap-3 border-y border-stone-200 py-4 text-sm text-stone-500 dark:border-stone-800 dark:text-stone-400">
                <span class="flex size-9 items-center justify-center rounded-full bg-stone-900 text-xs font-semibold text-white dark:bg-stone-700">
                    {{ \Illuminate\Support\Str::of($article['author'])->explode(' ')->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->implode('') }}
                </span>
                <span class="font-medium text-stone-700 dark:text-stone-300">{{ $article['author'] }}</span>
                <span>&middot;</span>
                <span>{{ $article['published_at']->translatedFormat('l, d F Y H:i') }} WIB</span>
                <span>&middot;</span>
                <span>{{ $article['reading_time'] }} menit baca</span>
            </div>

            <x-cover :article="$article" ratio="aspect-[16/9]" class="mt-8" />
            <p class="mt-2 text-xs text-stone-400">Ilustrasi: lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

            <div class="mt-8 space-y-5 text-[17px] leading-8 text-stone-700 dark:text-stone-300">
                @foreach ($article['body'] as $index => $paragraph)
                    <p @class(['first-letter:float-left first-letter:mr-2 first-letter:text-5xl first-letter:font-bold first-letter:leading-none first-letter:text-stone-900 dark:first-letter:text-white' => $index === 0])>
                        {{ $paragraph }}
                    </p>

                    @if ($index === 2)
                        <blockquote class="border-l-4 border-rose-500 bg-stone-100 px-5 py-4 text-lg font-medium italic text-stone-800 dark:bg-stone-900 dark:text-stone-200">
                            "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium."
                            <footer class="mt-2 text-sm font-normal not-italic text-stone-500 dark:text-stone-400">— {{ $article['author'] }}</footer>
                        </blockquote>
                    @endif
                @endforeach
            </div>

            <div class="mt-8 flex flex-wrap gap-2">
                @foreach ($article['tags'] as $tag)
                    <span class="rounded-full bg-stone-200 px-3 py-1 text-xs font-medium text-stone-600 dark:bg-stone-800 dark:text-stone-300">#{{ $tag }}</span>
                @endforeach
            </div>

            <section class="mt-12">
                <h2 class="mb-6 text-xl font-bold text-stone-900 dark:text-white">Berita terkait</h2>
                <div class="grid gap-8 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <x-article-card :article="$item" />
                    @endforeach
                </div>
            </section>
        </article>

        <x-sidebar :popular="$popular" />
    </div>
</x-layout>
