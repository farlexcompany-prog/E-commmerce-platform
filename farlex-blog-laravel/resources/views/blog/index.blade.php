<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | Farlex</title>
    <meta name="description" content="Health and pharmaceutical articles from Farlex.">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; color: #222; }
        header { background: #183b65; color: #fff; padding: 22px 30px; }
        header h1 { margin: 0; font-size: 26px; }
        main { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }
        .card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); display: flex; flex-direction: column; }
        .card img, .noimg { width: 100%; height: 180px; object-fit: cover; background: #dfe6ee; }
        .card .body { padding: 18px; flex: 1; display: flex; flex-direction: column; }
        .card h2 { font-size: 18px; margin: 0 0 8px; color: #183b65; }
        .card h2 a { color: inherit; text-decoration: none; }
        .date { font-size: 13px; color: #777; margin-bottom: 10px; }
        .card p { margin: 0 0 14px; line-height: 1.5; color: #444; flex: 1; }
        .more { color: #183b65; font-weight: bold; text-decoration: none; }
        .pager { margin: 34px 0; text-align: center; }
        .pager a { display: inline-block; margin: 0 6px; padding: 9px 16px; border-radius: 5px; background: #183b65; color: #fff; text-decoration: none; }
        .empty { text-align: center; padding: 60px 0; color: #666; }
    </style>
</head>
<body>
<header><h1>Farlex Blog</h1></header>
<main>
@if ($blogs->isEmpty())
    <p class="empty">No articles published yet. Please check back soon.</p>
@else
    <div class="grid">
        @foreach ($blogs as $blog)
            <article class="card">
                @if ($blog->image_url)
                    <img src="{{ asset($blog->image_url) }}" alt="{{ $blog->title }}" loading="lazy">
                @else
                    <div class="noimg"></div>
                @endif
                <div class="body">
                    <h2><a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a></h2>
                    <div class="date">{{ $blog->shown_date->format('d M Y') }}</div>
                    <p>{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($blog->content))), 160) }}</p>
                    <a class="more" href="{{ route('blog.show', $blog->slug) }}">Read more &rarr;</a>
                </div>
            </article>
        @endforeach
    </div>

    <div class="pager">
        @if ($blogs->previousPageUrl())
            <a href="{{ $blogs->previousPageUrl() }}">&larr; Newer</a>
        @endif
        @if ($blogs->nextPageUrl())
            <a href="{{ $blogs->nextPageUrl() }}">Older &rarr;</a>
        @endif
    </div>
@endif
</main>
</body>
</html>
