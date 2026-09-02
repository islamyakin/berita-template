<?php

namespace Tests\Feature;

use Tests\TestCase;

class WafLabTest extends TestCase
{
    public function test_halaman_lab_menampilkan_form_dan_pustaka_payload(): void
    {
        $response = $this->get(route('waf.index'));

        $response->assertOk();
        $response->assertSee('Lab Uji WAF');
        $response->assertSee('Pustaka payload XSS');
        $response->assertSee('Pustaka payload SQLi');
    }

    public function test_payload_xss_dipantulkan_dengan_escaping_penuh(): void
    {
        $payload = '<script>alert(1)</script>';

        $response = $this->post(route('waf.submit'), [
            'vector' => 'xss',
            'payload' => $payload,
        ]);

        $response->assertOk();
        // Payload TIDAK boleh muncul mentah (tanda tidak ada XSS di aplikasi).
        $response->assertDontSee($payload, false);
        // Payload muncul dalam bentuk ter-escape.
        $response->assertSee(e($payload), false);
        $response->assertSee('Signature XSS: terdeteksi', false);
    }

    public function test_payload_sqli_tidak_dieksekusi_hanya_dipantulkan(): void
    {
        $payload = "' OR 1=1--";

        $response = $this->post(route('waf.submit'), [
            'vector' => 'sqli',
            'payload' => $payload,
        ]);

        $response->assertOk();
        $response->assertSee('Signature SQLi: terdeteksi', false);
        $response->assertSee('Detail request yang diterima');
    }

    public function test_lab_dapat_diakses_via_get_tanpa_login(): void
    {
        $this->get(route('waf.submit', ['vector' => 'xss', 'payload' => '<svg/onload=alert(1)>']))
            ->assertOk()
            ->assertSee('Request lolos ke aplikasi');
    }
}
