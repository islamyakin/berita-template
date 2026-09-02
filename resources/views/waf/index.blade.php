<x-layout title="Lab Uji WAF" description="Harness uji WAF/CRS: kirim payload XSS & SQLi untuk memverifikasi pemblokiran WAF.">
    <div class="mb-8 rounded-2xl border border-amber-300 bg-amber-50 p-5 text-sm text-amber-900 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-200">
        <p class="font-semibold">⚠️ Lab pengujian keamanan</p>
        <p class="mt-1">
            Halaman ini untuk memverifikasi WAF (mis. ModSecurity + OWASP CRS) di depan aplikasi.
            Aplikasi hanya <strong>memantulkan input dengan escaping penuh</strong> — tidak ada eksekusi SQL
            dan tidak ada render HTML mentah, jadi aplikasi tidak rentan. Gunakan hanya pada lingkungan
            yang Anda kelola/uji sendiri.
        </p>
    </div>

    <header class="mb-8">
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Lab Uji WAF / CRS</h1>
        <p class="mt-2 max-w-2xl text-sm text-stone-500 dark:text-stone-400">
            Kirim payload umum XSS (aturan CRS 941xxx) dan SQL Injection (942xxx) lewat GET atau POST,
            lalu amati apakah WAF memblokirnya sebelum request mencapai aplikasi.
        </p>
    </header>

    <x-waf-form :xss-payloads="$xssPayloads" :sqli-payloads="$sqliPayloads" />
</x-layout>
