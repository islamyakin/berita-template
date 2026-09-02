<?php

namespace App\Http\Controllers;

use App\Support\WafTestPayloads;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Harness uji WAF/CRS. Menerima payload lewat GET (query string) maupun
 * POST (form body) — dua lokasi ARGS yang diinspeksi CRS — lalu
 * memantulkannya kembali dengan escaping penuh via Blade. Tidak ada
 * eksekusi SQL dan tidak ada render HTML mentah, sehingga aplikasi tetap
 * aman; yang diuji adalah lapisan WAF di depannya.
 */
class WafLabController extends Controller
{
    public function index(): View
    {
        return view('waf.index', [
            'xssPayloads' => WafTestPayloads::xss(),
            'sqliPayloads' => WafTestPayloads::sqli(),
        ]);
    }

    public function submit(Request $request): View
    {
        $payload = (string) $request->input('payload', '');
        $vector = $request->input('vector') === 'sqli' ? 'sqli' : 'xss';

        return view('waf.result', [
            'payload' => $payload,
            'vector' => $vector,
            'method' => $request->method(),
            'signals' => WafTestPayloads::classify($payload),
            'xssPayloads' => WafTestPayloads::xss(),
            'sqliPayloads' => WafTestPayloads::sqli(),
        ]);
    }
}
