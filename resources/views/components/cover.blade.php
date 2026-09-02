@props(['article', 'ratio' => 'aspect-[16/9]', 'showLabel' => true])

<div {{ $attributes->class(['relative overflow-hidden rounded-2xl bg-gradient-to-br '.$article['category']['cover'].' '.$ratio]) }}>
    <div class="absolute inset-0 opacity-30 [background-image:radial-gradient(circle_at_1px_1px,white_1px,transparent_0)] [background-size:16px_16px]"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
    @if ($showLabel)
        <span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-stone-900">
            {{ $article['category']['name'] }}
        </span>
    @endif
</div>
