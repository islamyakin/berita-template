<x-layout title="Uji WAF">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Uji WAF</h1>
        <p class="mt-2 max-w-2xl text-sm text-stone-500 dark:text-stone-400">
            Kirim apa saja lewat formulir ini. Kalau tulisan Anda terbaca sebagai serangan,
            permintaannya dihentikan di tepi jaringan dan halaman ini tidak pernah dijalankan —
            yang muncul adalah halaman 403 milik pelindung, bukan tampilan di bawah.
        </p>
    </header>

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <section>
            <form action="{{ route('uji-waf.store') }}" method="POST" class="rounded-2xl border border-stone-200 bg-white p-6 dark:border-stone-800 dark:bg-stone-900">
                @csrf

                <label for="payload" class="block text-sm font-semibold text-stone-800 dark:text-stone-200">
                    Isi kiriman
                </label>
                <textarea id="payload" name="payload" rows="4"
                    class="mt-2 w-full rounded-xl border border-stone-300 bg-stone-50 px-4 py-3 font-mono text-sm text-stone-800 focus:border-rose-500 focus:outline-none dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
                    placeholder="Halo, ini komentar biasa.">{{ old('payload') }}</textarea>

                <button type="submit"
                    class="mt-4 rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Kirim
                </button>
            </form>

            @if ($submitted !== null)
                <div class="mt-6 rounded-2xl border border-emerald-300 bg-emerald-50 p-6 dark:border-emerald-800 dark:bg-emerald-950/40">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">
                        Diteruskan ke aplikasi
                    </h2>
                    <p class="mt-1 text-sm text-emerald-800 dark:text-emerald-200">
                        Permintaan {{ $method }} ini lolos pemeriksaan dan sampai ke aplikasi.
                        Isinya ditampilkan sebagai teks, tidak pernah sebagai kode.
                    </p>
                    <pre class="mt-4 overflow-x-auto rounded-xl bg-white p-4 font-mono text-xs text-stone-800 dark:bg-stone-950 dark:text-stone-100">{{ $submitted }}</pre>
                    <p class="mt-3 text-xs text-emerald-700 dark:text-emerald-300">
                        Panjang: {{ strlen($submitted) }} karakter.
                    </p>
                </div>
            @endif
        </section>

        <aside class="rounded-2xl border border-stone-200 bg-white p-6 text-sm dark:border-stone-800 dark:bg-stone-900">
            <h2 class="font-semibold text-stone-900 dark:text-white">Cara membacanya</h2>

            <dl class="mt-4 space-y-4 text-stone-600 dark:text-stone-300">
                <div>
                    <dt class="font-semibold text-stone-800 dark:text-stone-100">403 dari pelindung</dt>
                    <dd>Kiriman dihentikan sebelum aplikasi melihatnya. Inilah yang diharapkan untuk muatan serangan.</dd>
                </div>
                <div>
                    <dt class="font-semibold text-stone-800 dark:text-stone-100">Kotak hijau di samping</dt>
                    <dd>Kiriman dinilai wajar dan diteruskan. Teks apa pun ditampilkan sebagai teks — bukan lubang keamanan.</dd>
                </div>
            </dl>

            <p class="mt-5 text-xs text-stone-500 dark:text-stone-400">
                Halaman ini untuk menguji pelindung di aplikasi Anda sendiri. Menguji milik orang lain
                tanpa izin adalah hal yang berbeda sama sekali.
            </p>
        </aside>
    </div>
</x-layout>
