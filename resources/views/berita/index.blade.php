<x-layout>
    <section class="mb-10 flex items-center gap-4 overflow-hidden rounded-2xl border border-stone-200 bg-white px-4 py-3 dark:border-stone-800 dark:bg-stone-900">
        <span class="shrink-0 rounded-full bg-rose-600 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white">Terkini</span>
        <div class="flex gap-6 overflow-x-auto text-sm text-stone-600 dark:text-stone-300">
            @foreach ($articles->take(4) as $ticker)
                <a href="{{ route('berita.show', $ticker['slug']) }}" class="whitespace-nowrap transition hover:text-rose-600 dark:hover:text-rose-400">
                    {{ \Illuminate\Support\Str::limit($ticker['title'], 48) }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="grid gap-8 lg:grid-cols-3">
        <article class="lg:col-span-2">
            <a href="{{ route('berita.show', $headline['slug']) }}" class="group block">
                <x-cover :article="$headline" ratio="aspect-[16/9]" class="transition duration-300 group-hover:brightness-105" />
                <div class="mt-5 flex flex-wrap items-center gap-3 text-xs">
                    <span class="rounded-full px-2 py-0.5 font-semibold {{ $headline['category']['badge'] }}">{{ $headline['category']['name'] }}</span>
                    <span class="text-stone-400">{{ $headline['published_at']->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                <h1 class="mt-3 text-3xl font-bold leading-tight text-stone-900 transition group-hover:text-rose-600 dark:text-white dark:group-hover:text-rose-400 sm:text-4xl">
                    {{ $headline['title'] }}
                </h1>
                <p class="mt-3 text-base text-stone-600 dark:text-stone-400">{{ $headline['excerpt'] }}</p>
                <p class="mt-4 text-sm text-stone-400">{{ $headline['author'] }} &middot; {{ $headline['reading_time'] }} menit baca</p>
            </a>
        </article>

        <div class="space-y-6 lg:border-l lg:border-stone-200 lg:pl-8 dark:lg:border-stone-800">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-stone-900 dark:text-white">Pilihan redaksi</h2>
            @foreach ($editorPicks as $pick)
                <x-article-card :article="$pick" class="border-b border-stone-200 pb-6 last:border-0 last:pb-0 dark:border-stone-800" />
            @endforeach
        </div>
    </section>

    <hr class="my-12 border-stone-200 dark:border-stone-800">

    <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <section>
            <div class="mb-6 flex items-baseline justify-between">
                <h2 class="text-xl font-bold text-stone-900 dark:text-white">Berita terbaru</h2>
                <span class="text-sm text-stone-400">{{ $articles->count() }} artikel</span>
            </div>

            <div class="grid gap-8 sm:grid-cols-2">
                @foreach ($articles as $article)
                    <x-article-card :article="$article" />
                @endforeach
            </div>
        </section>

        <x-sidebar :popular="$popular" />
    </div>
</x-layout>
