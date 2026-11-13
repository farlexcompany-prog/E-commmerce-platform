<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->meta_title ?: $blog->title }} | Farlex</title>
    <meta name="description" content="{{ $blog->meta_description ?: \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', $blog->content)), 155) }}">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; color: #222; }
        header { background: #183b65; color: #fff; padding: 22px 30px; }
        header a { color: #fff; text-decoration: none; font-size: 22px; font-weight: bold; }
        article { max-width: 780px; margin: 30px auto; background: #fff; padding: 34px; border-radius: 10px; }
        h1 { color: #183b65; margin-top: 0; line-height: 1.3; }
        .date { color: #777; font-size: 14px; margin-bottom: 20px; }
        article img { width: 100%; border-radius: 8px; margin-bottom: 22px; }
        .content { line-height: 1.75; font-size: 17px; }
        .back { display: inline-block; margin-top: 26px; color: #183b65; font-weight: bold; text-decoration: none; }
    </style>
</head>
<body>
<header><a href="{{ route('blog.index') }}">Farlex Blog</a></header>
<article>
    <h1>{{ $blog->title }}</h1>
    <div class="date">{{ $blog->shown_date->format('d M Y') }}</div>
    @if ($blog->image_url)
        <img src="{{ asset($blog->image_url) }}" alt="{{ $blog->title }}">
    @endif
    <div class="content">{!! nl2br(e($blog->content)) !!}</div>
    <a class="back" href="{{ route('blog.index') }}">&larr; All articles</a>
</article>
</body>
</html>
