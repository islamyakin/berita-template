@props(['popular'])

<aside class="space-y-8">
    <section class="rounded-2xl border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-stone-900 dark:text-white">Terpopuler</h2>
        <ol class="mt-4 space-y-4">
            @foreach ($popular as $index => $article)
                <li class="flex gap-3">
                    <span class="text-2xl font-bold leading-none text-stone-200 dark:text-stone-700">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <a href="{{ route('berita.show', $article['slug']) }}" class="text-sm font-medium leading-snug text-stone-800 transition hover:text-rose-600 dark:text-stone-200 dark:hover:text-rose-400">
                            {{ $article['title'] }}
                        </a>
                        <p class="mt-1 text-xs text-stone-400">{{ number_format($article['views'], 0, ',', '.') }} pembaca</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="rounded-2xl border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-stone-900 dark:text-white">Kanal</h2>
        <ul class="mt-4 space-y-2">
            @foreach (\App\Support\NewsRepository::categories() as $category)
                <li>
                    <a href="{{ route('berita.kategori', $category['slug']) }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition hover:bg-stone-100 dark:hover:bg-stone-800">
                        <span>{{ $category['name'] }}</span>
                        <span class="text-xs text-stone-400">{{ \App\Support\NewsRepository::byCategory($category['slug'])->count() }} artikel</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="rounded-2xl bg-stone-900 p-5 text-white dark:bg-stone-800">
        <h2 class="text-sm font-semibold uppercase tracking-wider">Tentang halaman ini</h2>
        <p class="mt-3 text-sm text-stone-300">
            Template berita statis: seluruh data berasal dari array PHP, tanpa basis data dan tanpa autentikasi.
            Semua pengunjung dapat membaca langsung tanpa login.
        </p>
    </section>
</aside>
