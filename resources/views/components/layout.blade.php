@props([
    'title' => null,
    'description' => 'Portal berita statis berisi konten lorem ipsum. Tanpa login, tanpa basis data.',
])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $description }}">

        <title>{{ $title ? $title.' — Lipsum Post' : 'Lipsum Post — Berita Lorem Ipsum' }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-stone-50 font-sans text-stone-800 antialiased dark:bg-stone-950 dark:text-stone-200">
        <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-stone-900 focus:px-4 focus:py-2 focus:text-white">
            Lompat ke konten
        </a>

        <header class="border-b border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
            <div class="border-b border-stone-200 bg-stone-100/70 text-xs dark:border-stone-800 dark:bg-stone-900/60">
                <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-2 text-stone-500 dark:text-stone-400">
                    <p>{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="hidden sm:block">Akses bebas &middot; tanpa akun &middot; seluruh isi lorem ipsum</p>
                </div>
            </div>

            <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-6 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('berita.index') }}" class="group">
                    <span class="block text-3xl font-semibold tracking-tight text-stone-900 dark:text-white">
                        Lipsum<span class="text-rose-600 dark:text-rose-400">Post</span>
                    </span>
                    <span class="text-xs uppercase tracking-[0.3em] text-stone-400">Dolor sit amet</span>
                </a>

                <form action="{{ route('berita.index') }}" method="GET" class="flex w-full max-w-sm items-center gap-2">
                    <label for="q" class="sr-only">Cari berita</label>
                    <input
                        id="q"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari berita lorem ipsum…"
                        class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-2 text-sm outline-none placeholder:text-stone-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 dark:border-stone-700 dark:bg-stone-950"
                    >
                    <button type="submit" class="shrink-0 rounded-full bg-stone-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-600 dark:bg-white dark:text-stone-900 dark:hover:bg-rose-400">
                        Cari
                    </button>
                </form>
            </div>

            <nav class="border-t border-stone-200 dark:border-stone-800">
                <div class="mx-auto flex max-w-6xl gap-1 overflow-x-auto px-4 py-2 text-sm font-medium">
                    <a href="{{ route('berita.index') }}"
                       @class([
                           'whitespace-nowrap rounded-full px-3 py-1.5 transition',
                           'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => request()->routeIs('berita.index'),
                           'text-stone-600 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-800' => ! request()->routeIs('berita.index'),
                       ])>
                        Terbaru
                    </a>
                    @foreach ($navCategories as $navCategory)
                        <a href="{{ route('berita.kategori', $navCategory['slug']) }}"
                           @class([
                               'whitespace-nowrap rounded-full px-3 py-1.5 transition',
                               'bg-stone-900 text-white dark:bg-white dark:text-stone-900' => request()->routeIs('berita.kategori') && request()->route('slug') === $navCategory['slug'],
                               'text-stone-600 hover:bg-stone-100 dark:text-stone-300 dark:hover:bg-stone-800' => ! (request()->routeIs('berita.kategori') && request()->route('slug') === $navCategory['slug']),
                           ])>
                            {{ $navCategory['name'] }}
                        </a>
                    @endforeach
                </div>
            </nav>
        </header>

        <main id="konten" class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
            {{ $slot }}
        </main>

        <footer class="mt-12 border-t border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 sm:grid-cols-3">
                <div>
                    <p class="text-xl font-semibold text-stone-900 dark:text-white">Lipsum<span class="text-rose-600 dark:text-rose-400">Post</span></p>
                    <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">
                        Halaman statis contoh. Seluruh judul, penulis, dan isi berita adalah teks lorem ipsum.
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-stone-900 dark:text-white">Kanal</p>
                    <ul class="mt-3 space-y-2 text-sm text-stone-500 dark:text-stone-400">
                        @foreach ($navCategories as $navCategory)
                            <li>
                                <a href="{{ route('berita.kategori', $navCategory['slug']) }}" class="hover:text-rose-600 dark:hover:text-rose-400">
                                    {{ $navCategory['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-stone-900 dark:text-white">Redaksi</p>
                    <p class="mt-3 text-sm text-stone-500 dark:text-stone-400">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>
                        Jl. Ipsum No. 21, Dolor &middot; redaksi@lipsum.test
                    </p>
                    <a href="{{ route('waf.index') }}" class="mt-3 inline-block text-sm text-stone-500 hover:text-rose-600 dark:text-stone-400 dark:hover:text-rose-400">
                        Lab Uji WAF/CRS &rarr;
                    </a>
                </div>
            </div>
            <div class="border-t border-stone-200 py-4 text-center text-xs text-stone-400 dark:border-stone-800">
                &copy; {{ now()->year }} LipsumPost. Halaman demo tanpa login.
            </div>
        </footer>
        @stack('scripts')
    </body>
</html>
