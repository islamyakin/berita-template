<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Kumpulan payload uji untuk memverifikasi Web Application Firewall
 * (mis. ModSecurity + OWASP CRS). Payload di sini HANYA dipantulkan
 * kembali oleh aplikasi dengan escaping penuh — tidak pernah dieksekusi
 * sebagai SQL maupun dirender sebagai HTML mentah. Tujuannya menghasilkan
 * request yang seharusnya ditandai/diblokir CRS sebelum mencapai app.
 */
class WafTestPayloads
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function xss(): Collection
    {
        return collect([
            ['label' => 'Script tag klasik', 'crs' => '941110', 'payload' => '<script>alert(1)</script>'],
            ['label' => 'IMG onerror', 'crs' => '941160', 'payload' => '<img src=x onerror=alert(document.cookie)>'],
            ['label' => 'SVG onload', 'crs' => '941160', 'payload' => '<svg/onload=alert(1)>'],
            ['label' => 'Atribut break-out', 'crs' => '941120', 'payload' => '"><script>alert(String.fromCharCode(88,83,83))</script>'],
            ['label' => 'javascript: URI', 'crs' => '941200', 'payload' => 'javascript:alert(document.domain)'],
            ['label' => 'Body onload', 'crs' => '941150', 'payload' => "<body onload=alert('XSS')>"],
            ['label' => 'Iframe javascript', 'crs' => '941170', 'payload' => '<iframe src="javascript:alert(1)"></iframe>'],
            ['label' => 'Event handler inline', 'crs' => '941100', 'payload' => '<div onmouseover="alert(1)">hover</div>'],
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function sqli(): Collection
    {
        return collect([
            ['label' => 'Tautologi OR', 'crs' => '942100', 'payload' => "' OR '1'='1"],
            ['label' => 'Komentar bypass', 'crs' => '942100', 'payload' => "' OR 1=1--"],
            ['label' => 'Login bypass', 'crs' => '942130', 'payload' => "admin'--"],
            ['label' => 'UNION SELECT', 'crs' => '942190', 'payload' => 'UNION SELECT username, password FROM users--'],
            ['label' => 'Time-based blind', 'crs' => '942160', 'payload' => "1' AND SLEEP(5)--"],
            ['label' => 'Stacked query', 'crs' => '942110', 'payload' => "1'; DROP TABLE users;--"],
            ['label' => 'Boolean paren', 'crs' => '942120', 'payload' => '1) OR (1=1'],
            ['label' => 'URL-encoded OR', 'crs' => '942100', 'payload' => '%27%20OR%20%271%27=%271'],
        ]);
    }

    /**
     * Heuristik informatif (bukan pengganti WAF) untuk menandai apakah
     * input menyerupai signature XSS/SQLi. Dipakai hanya untuk menampilkan
     * catatan di halaman hasil, tidak menentukan pemblokiran.
     */
    public static function classify(string $input): array
    {
        $needle = mb_strtolower($input);

        $xss = collect(['<script', 'onerror', 'onload', 'onmouseover', 'javascript:', '<svg', '<img', '<iframe', 'alert('])
            ->contains(fn (string $token) => str_contains($needle, $token));

        $sqli = collect(["' or ", ' or 1=1', 'union select', 'sleep(', 'drop table', "'--", '--', '%27'])
            ->contains(fn (string $token) => str_contains($needle, $token));

        return ['xss' => $xss, 'sqli' => $sqli];
    }
}
