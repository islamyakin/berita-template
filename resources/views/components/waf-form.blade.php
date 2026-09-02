@props(['xssPayloads', 'sqliPayloads', 'payload' => '', 'vector' => 'xss'])

<form id="waf-form" action="{{ route('waf.submit') }}" method="POST" class="rounded-2xl border border-stone-200 bg-white p-6 dark:border-stone-800 dark:bg-stone-900">
    @csrf

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="vector" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Vektor uji</label>
            <select id="vector" name="vector" class="mt-1 w-full rounded-lg border border-stone-300 bg-stone-50 px-3 py-2 text-sm outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 dark:border-stone-700 dark:bg-stone-950">
                <option value="xss" @selected($vector === 'xss')>XSS (CRS 941xxx)</option>
                <option value="sqli" @selected($vector === 'sqli')>SQL Injection (CRS 942xxx)</option>
            </select>
        </div>

        <div>
            <label for="method" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Metode request</label>
            <select id="method" class="mt-1 w-full rounded-lg border border-stone-300 bg-stone-50 px-3 py-2 text-sm outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 dark:border-stone-700 dark:bg-stone-950">
                <option value="POST">POST (body / ARGS_POST)</option>
                <option value="GET">GET (query / ARGS_GET)</option>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <label for="payload" class="block text-sm font-medium text-stone-700 dark:text-stone-300">Payload</label>
        <textarea id="payload" name="payload" rows="3"
            class="mt-1 w-full rounded-lg border border-stone-300 bg-stone-50 px-3 py-2 font-mono text-sm outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 dark:border-stone-700 dark:bg-stone-950"
            placeholder="Tempel atau pilih payload dari pustaka di bawah…">{{ $payload }}</textarea>
    </div>

    <button type="submit" class="mt-4 rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
        Kirim payload ke aplikasi
    </button>

    <p class="mt-3 text-xs text-stone-500 dark:text-stone-400">
        Bila WAF aktif dan mengenali payload, request diblokir (mis. HTTP 403) dan halaman hasil tidak akan muncul.
        Bila halaman hasil tampil, berarti request lolos sampai ke aplikasi.
    </p>
</form>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section>
        <h2 class="text-sm font-semibold uppercase tracking-wider text-stone-900 dark:text-white">Pustaka payload XSS</h2>
        <div class="mt-3 space-y-2">
            @foreach ($xssPayloads as $item)
                <button type="button"
                    class="waf-pick block w-full rounded-lg border border-stone-200 bg-white px-3 py-2 text-left transition hover:border-rose-400 dark:border-stone-800 dark:bg-stone-900"
                    data-vector="xss"
                    data-payload="{{ $item['payload'] }}">
                    <span class="flex items-center justify-between">
                        <span class="text-sm font-medium text-stone-800 dark:text-stone-200">{{ $item['label'] }}</span>
                        <span class="rounded bg-stone-100 px-1.5 py-0.5 font-mono text-[10px] text-stone-500 dark:bg-stone-800 dark:text-stone-400">CRS {{ $item['crs'] }}</span>
                    </span>
                    <code class="mt-1 block truncate font-mono text-xs text-rose-600 dark:text-rose-400">{{ $item['payload'] }}</code>
                </button>
            @endforeach
        </div>
    </section>

    <section>
        <h2 class="text-sm font-semibold uppercase tracking-wider text-stone-900 dark:text-white">Pustaka payload SQLi</h2>
        <div class="mt-3 space-y-2">
            @foreach ($sqliPayloads as $item)
                <button type="button"
                    class="waf-pick block w-full rounded-lg border border-stone-200 bg-white px-3 py-2 text-left transition hover:border-rose-400 dark:border-stone-800 dark:bg-stone-900"
                    data-vector="sqli"
                    data-payload="{{ $item['payload'] }}">
                    <span class="flex items-center justify-between">
                        <span class="text-sm font-medium text-stone-800 dark:text-stone-200">{{ $item['label'] }}</span>
                        <span class="rounded bg-stone-100 px-1.5 py-0.5 font-mono text-[10px] text-stone-500 dark:bg-stone-800 dark:text-stone-400">CRS {{ $item['crs'] }}</span>
                    </span>
                    <code class="mt-1 block truncate font-mono text-xs text-emerald-600 dark:text-emerald-400">{{ $item['payload'] }}</code>
                </button>
            @endforeach
        </div>
    </section>
</div>

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('waf-form');
        const methodSelect = document.getElementById('method');
        const vectorSelect = document.getElementById('vector');
        const payloadField = document.getElementById('payload');
        const csrf = form.querySelector('input[name="_token"]');

        // GET tidak butuh token CSRF; nonaktifkan agar tak bocor ke query string.
        function syncMethod() {
            const isGet = methodSelect.value === 'GET';
            form.method = isGet ? 'GET' : 'POST';
            if (csrf) csrf.disabled = isGet;
        }
        methodSelect.addEventListener('change', syncMethod);
        syncMethod();

        document.querySelectorAll('.waf-pick').forEach(function (btn) {
            btn.addEventListener('click', function () {
                payloadField.value = btn.dataset.payload;
                vectorSelect.value = btn.dataset.vector;
                payloadField.focus();
            });
        });
    })();
</script>
@endpush
