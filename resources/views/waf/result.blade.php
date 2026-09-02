<x-layout title="Hasil Uji WAF">
    <div class="mb-8 rounded-2xl border border-emerald-300 bg-emerald-50 p-5 dark:border-emerald-500/40 dark:bg-emerald-500/10">
        <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">Request lolos ke aplikasi</p>
        <p class="mt-1 text-sm text-emerald-700 dark:text-emerald-200">
            Halaman ini muncul, artinya WAF <strong>tidak memblokir</strong> request (atau berjalan dalam mode deteksi saja / tidak ada WAF).
            Bila WAF dalam mode blocking mengenali payload, Anda akan menerima 403 dan tidak sampai ke halaman ini.
        </p>
    </div>

    <section class="rounded-2xl border border-stone-200 bg-white p-6 dark:border-stone-800 dark:bg-stone-900">
        <h1 class="text-xl font-bold text-stone-900 dark:text-white">Detail request yang diterima</h1>

        <dl class="mt-4 grid gap-4 sm:grid-cols-3">
            <div>
                <dt class="text-xs uppercase tracking-wider text-stone-400">Metode</dt>
                <dd class="mt-1 font-mono text-sm text-stone-800 dark:text-stone-200">{{ $method }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wider text-stone-400">Vektor</dt>
                <dd class="mt-1 font-mono text-sm text-stone-800 dark:text-stone-200">{{ strtoupper($vector) }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wider text-stone-400">Panjang payload</dt>
                <dd class="mt-1 font-mono text-sm text-stone-800 dark:text-stone-200">{{ strlen($payload) }} byte</dd>
            </div>
        </dl>

        <div class="mt-5">
            <p class="text-xs uppercase tracking-wider text-stone-400">Payload yang diterima (ditampilkan dengan escaping penuh)</p>
            {{-- {{ }} melakukan escaping HTML, sehingga payload TIDAK dieksekusi oleh browser. --}}
            <pre class="mt-2 overflow-x-auto rounded-lg bg-stone-900 p-4 font-mono text-sm text-stone-100 dark:bg-black"><code>{{ $payload === '' ? '(kosong)' : $payload }}</code></pre>
        </div>

        <div class="mt-5 flex flex-wrap gap-2 text-xs">
            <span class="rounded-full px-3 py-1 font-medium {{ $signals['xss'] ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300' : 'bg-stone-100 text-stone-500 dark:bg-stone-800 dark:text-stone-400' }}">
                Signature XSS: {{ $signals['xss'] ? 'terdeteksi (heuristik)' : 'tidak terdeteksi' }}
            </span>
            <span class="rounded-full px-3 py-1 font-medium {{ $signals['sqli'] ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-stone-100 text-stone-500 dark:bg-stone-800 dark:text-stone-400' }}">
                Signature SQLi: {{ $signals['sqli'] ? 'terdeteksi (heuristik)' : 'tidak terdeteksi' }}
            </span>
        </div>
        <p class="mt-3 text-xs text-stone-400">
            Catatan: label di atas hanya heuristik informatif di sisi aplikasi, bukan keputusan WAF. Penentu sebenarnya
            adalah apakah request diblokir sebelum sampai ke sini.
        </p>
    </section>

    <div class="mt-8">
        <h2 class="mb-4 text-lg font-bold text-stone-900 dark:text-white">Uji payload lain</h2>
        <x-waf-form :xss-payloads="$xssPayloads" :sqli-payloads="$sqliPayloads" :payload="$payload" :vector="$vector" />
    </div>
</x-layout>
