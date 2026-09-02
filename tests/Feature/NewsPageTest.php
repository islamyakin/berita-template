<?php

namespace Tests\Feature;

use App\Support\NewsRepository;
use Tests\TestCase;

class NewsPageTest extends TestCase
{
    public function test_beranda_menampilkan_headline_tanpa_login(): void
    {
        $response = $this->get(route('berita.index'));

        $response->assertOk();
        $response->assertSee(NewsRepository::headline()['title']);
        $response->assertSee('Berita terbaru');
    }

    public function test_halaman_kategori_hanya_menampilkan_artikel_kanal_terkait(): void
    {
        $response = $this->get(route('berita.kategori', 'teknologi'));

        $response->assertOk();
        $response->assertSee(NewsRepository::byCategory('teknologi')->first()['title']);
    }

    public function test_kategori_tidak_dikenal_mengembalikan_404(): void
    {
        $this->get(route('berita.kategori', 'tidak-ada'))->assertNotFound();
    }

    public function test_halaman_detail_menampilkan_isi_artikel(): void
    {
        $article = NewsRepository::all()->first();

        $response = $this->get(route('berita.show', $article['slug']));

        $response->assertOk();
        $response->assertSee($article['title']);
        $response->assertSee('Berita terkait');
    }

    public function test_artikel_tidak_dikenal_mengembalikan_404(): void
    {
        $this->get(route('berita.show', 'artikel-hantu'))->assertNotFound();
    }

    public function test_pencarian_menyaring_berita_berdasarkan_kata_kunci(): void
    {
        $response = $this->get(route('berita.index', ['q' => 'perspiciatis']));

        $response->assertOk();
        $response->assertSee('Hasil pencarian');
        $response->assertSee('Sed ut perspiciatis unde omnis iste natus error sit voluptatem');
    }
}
