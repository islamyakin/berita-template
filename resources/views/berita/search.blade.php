<x-layout :title="'Pencarian: '.$keyword">
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Hasil pencarian</h1>
        <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">
            {{ $articles->count() }} artikel cocok dengan kata kunci <span class="font-semibold text-stone-800 dark:text-stone-200">"{{ $keyword }}"</span>.
            <a href="{{ route('berita.index') }}" class="text-rose-600 hover:underline dark:text-rose-400">Kembali ke beranda</a>
        </p>
    </header>

    <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <section class="grid gap-8 sm:grid-cols-2">
            @forelse ($articles as $article)
                <x-article-card :article="$article" />
            @empty
                <p class="sm:col-span-2 rounded-2xl border border-dashed border-stone-300 p-10 text-center text-sm text-stone-500 dark:border-stone-700 dark:text-stone-400">
                    Tidak ada berita yang cocok. Coba kata kunci lain seperti <em>lorem</em>, <em>dolor</em>, atau <em>voluptatem</em>.
                </p>
            @endforelse
        </section>

        <x-sidebar :popular="$popular" />
    </div>
</x-layout>
