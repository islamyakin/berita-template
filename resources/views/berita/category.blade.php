<x-layout :title="$category['name']">
    <header class="mb-10 rounded-2xl border border-stone-200 bg-white p-8 dark:border-stone-800 dark:bg-stone-900">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-stone-400">Kanal</p>
        <h1 class="mt-2 text-3xl font-bold text-stone-900 dark:text-white">{{ $category['name'] }}</h1>
        <p class="mt-3 max-w-2xl text-sm text-stone-500 dark:text-stone-400">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Kumpulan {{ $articles->count() }} artikel lorem ipsum pada kanal ini.
        </p>
    </header>

    <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <section class="grid gap-8 sm:grid-cols-2">
            @foreach ($articles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </section>

        <x-sidebar :popular="$popular" />
    </div>
</x-layout>
