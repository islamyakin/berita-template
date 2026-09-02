<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Sumber data berita statis. Tidak menyentuh database sama sekali,
 * seluruh konten adalah lorem ipsum untuk keperluan template.
 */
class NewsRepository
{
    /** @var array<int, string> */
    private const LOREM = [
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
        'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.',
        'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident.',
        'Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus.',
        'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur, ut labore et dolore magnam aliquam.',
        'Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus ut aut reiciendis.',
    ];

    /**
     * Daftar kategori beserta warna aksen yang dipakai di seluruh halaman.
     *
     * @return Collection<int, array<string, string>>
     */
    public static function categories(): Collection
    {
        return collect([
            ['slug' => 'nasional', 'name' => 'Nasional', 'badge' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300', 'cover' => 'from-rose-500 to-orange-400'],
            ['slug' => 'ekonomi', 'name' => 'Ekonomi', 'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300', 'cover' => 'from-emerald-500 to-teal-400'],
            ['slug' => 'teknologi', 'name' => 'Teknologi', 'badge' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300', 'cover' => 'from-indigo-500 to-sky-400'],
            ['slug' => 'olahraga', 'name' => 'Olahraga', 'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300', 'cover' => 'from-amber-500 to-yellow-400'],
            ['slug' => 'gaya-hidup', 'name' => 'Gaya Hidup', 'badge' => 'bg-fuchsia-100 text-fuchsia-700 dark:bg-fuchsia-500/15 dark:text-fuchsia-300', 'cover' => 'from-fuchsia-500 to-purple-400'],
        ]);
    }

    public static function category(string $slug): ?array
    {
        return self::categories()->firstWhere('slug', $slug);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function all(): Collection
    {
        return collect(self::seed())
            ->map(fn (array $article, int $index) => [
                ...$article,
                'category' => self::category($article['category_slug']),
                'published_at' => Carbon::parse($article['published_at']),
                'body' => self::paragraphs($index, 6),
                'reading_time' => 3 + ($index % 5),
                'views' => 1200 + ($index * 437) % 8000,
            ])
            ->sortByDesc('published_at')
            ->values();
    }

    public static function headline(): array
    {
        return self::all()->firstWhere('featured', true) ?? self::all()->first();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function latest(int $limit = 9, ?string $exceptSlug = null): Collection
    {
        return self::all()
            ->when($exceptSlug, fn (Collection $items) => $items->where('slug', '!=', $exceptSlug))
            ->take($limit)
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function popular(int $limit = 5): Collection
    {
        return self::all()->sortByDesc('views')->take($limit)->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function byCategory(string $slug): Collection
    {
        return self::all()->where('category_slug', $slug)->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function search(string $keyword): Collection
    {
        $keyword = mb_strtolower(trim($keyword));

        return self::all()
            ->filter(fn (array $article) => str_contains(mb_strtolower($article['title'].' '.$article['excerpt']), $keyword))
            ->values();
    }

    public static function find(string $slug): ?array
    {
        return self::all()->firstWhere('slug', $slug);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function related(array $article, int $limit = 3): Collection
    {
        $sameCategory = self::byCategory($article['category_slug'])
            ->where('slug', '!=', $article['slug']);

        return $sameCategory
            ->concat(self::latest(12, $article['slug'])->whereNotIn('slug', $sameCategory->pluck('slug')))
            ->take($limit)
            ->values();
    }

    /**
     * Membangun paragraf lorem ipsum secara deterministik agar setiap
     * artikel punya isi yang berbeda tetapi tetap stabil antar-request.
     *
     * @return array<int, string>
     */
    private static function paragraphs(int $seed, int $count): array
    {
        $total = count(self::LOREM);

        return collect(range(0, $count - 1))
            ->map(fn (int $i) => self::LOREM[($seed + $i * 3) % $total].' '.self::LOREM[($seed + $i * 5 + 1) % $total])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function seed(): array
    {
        return [
            [
                'slug' => 'lorem-ipsum-dolor-sit-amet-consectetur',
                'title' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod',
                'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat duis aute irure.',
                'category_slug' => 'nasional',
                'author' => 'Redaksi Lipsum',
                'published_at' => '2026-09-02 07:30',
                'featured' => true,
                'tags' => ['Lorem', 'Ipsum', 'Dolor'],
            ],
            [
                'slug' => 'sed-ut-perspiciatis-unde-omnis-iste-natus',
                'title' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem',
                'excerpt' => 'Totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
                'category_slug' => 'ekonomi',
                'author' => 'Amet Consectetur',
                'published_at' => '2026-09-02 06:10',
                'featured' => false,
                'tags' => ['Voluptatem', 'Aperiam'],
            ],
            [
                'slug' => 'neque-porro-quisquam-est-qui-dolorem-ipsum',
                'title' => 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet',
                'excerpt' => 'Numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem sequi nesciunt.',
                'category_slug' => 'teknologi',
                'author' => 'Dolor Sitamet',
                'published_at' => '2026-09-01 19:45',
                'featured' => false,
                'tags' => ['Tempora', 'Magnam'],
            ],
            [
                'slug' => 'at-vero-eos-et-accusamus-et-iusto-odio',
                'title' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis',
                'excerpt' => 'Praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate.',
                'category_slug' => 'olahraga',
                'author' => 'Ipsum Laboris',
                'published_at' => '2026-09-01 15:20',
                'featured' => false,
                'tags' => ['Dolores', 'Molestias'],
            ],
            [
                'slug' => 'temporibus-autem-quibusdam-et-aut-officiis',
                'title' => 'Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus',
                'excerpt' => 'Saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae itaque earum rerum hic tenetur.',
                'category_slug' => 'gaya-hidup',
                'author' => 'Nostrud Exercitation',
                'published_at' => '2026-09-01 11:05',
                'featured' => false,
                'tags' => ['Officiis', 'Necessitatibus'],
            ],
            [
                'slug' => 'nam-libero-tempore-cum-soluta-nobis-eligendi',
                'title' => 'Nam libero tempore cum soluta nobis est eligendi optio cumque nihil impedit',
                'excerpt' => 'Quo minus id quod maxime placeat facere possimus omnis voluptas assumenda est omnis dolor repellendus.',
                'category_slug' => 'ekonomi',
                'author' => 'Veniam Quis',
                'published_at' => '2026-08-31 20:40',
                'featured' => false,
                'tags' => ['Eligendi', 'Voluptas'],
            ],
            [
                'slug' => 'quis-autem-vel-eum-iure-reprehenderit',
                'title' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse',
                'excerpt' => 'Quam nihil molestiae consequatur vel illum qui dolorem eum fugiat quo voluptas nulla pariatur sed quia.',
                'category_slug' => 'nasional',
                'author' => 'Commodo Consequat',
                'published_at' => '2026-08-31 09:15',
                'featured' => false,
                'tags' => ['Reprehenderit', 'Pariatur'],
            ],
            [
                'slug' => 'excepteur-sint-occaecat-cupidatat-non-proident',
                'title' => 'Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia',
                'excerpt' => 'Deserunt mollit anim id est laborum et dolorum fuga harum quidem rerum facilis est et expedita distinctio.',
                'category_slug' => 'teknologi',
                'author' => 'Aliquip Exea',
                'published_at' => '2026-08-30 17:55',
                'featured' => false,
                'tags' => ['Laborum', 'Distinctio'],
            ],
            [
                'slug' => 'ut-enim-ad-minima-veniam-quis-nostrum',
                'title' => 'Ut enim ad minima veniam quis nostrum exercitationem ullam corporis suscipit',
                'excerpt' => 'Laboriosam nisi ut aliquid ex ea commodi consequatur autem vel eum iure reprehenderit qui in ea voluptate.',
                'category_slug' => 'olahraga',
                'author' => 'Minim Veniam',
                'published_at' => '2026-08-30 08:25',
                'featured' => false,
                'tags' => ['Corporis', 'Suscipit'],
            ],
            [
                'slug' => 'itaque-earum-rerum-hic-tenetur-a-sapiente',
                'title' => 'Itaque earum rerum hic tenetur a sapiente delectus ut aut reiciendis',
                'excerpt' => 'Voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat nam libero tempore.',
                'category_slug' => 'gaya-hidup',
                'author' => 'Sapiente Delectus',
                'published_at' => '2026-08-29 14:00',
                'featured' => false,
                'tags' => ['Delectus', 'Maiores'],
            ],
            [
                'slug' => 'duis-aute-irure-dolor-in-reprehenderit',
                'title' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum',
                'excerpt' => 'Dolore eu fugiat nulla pariatur excepteur sint occaecat cupidatat non proident sunt in culpa qui officia.',
                'category_slug' => 'ekonomi',
                'author' => 'Cillum Dolore',
                'published_at' => '2026-08-29 10:30',
                'featured' => false,
                'tags' => ['Fugiat', 'Cillum'],
            ],
            [
                'slug' => 'consectetur-adipiscing-elit-sed-do-eiusmod-tempor',
                'title' => 'Consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore',
                'excerpt' => 'Et dolore magna aliqua ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip.',
                'category_slug' => 'teknologi',
                'author' => 'Eiusmod Tempor',
                'published_at' => '2026-08-28 16:45',
                'featured' => false,
                'tags' => ['Incididunt', 'Aliqua'],
            ],
        ];
    }
}
