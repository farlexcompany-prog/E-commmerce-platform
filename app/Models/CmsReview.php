<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsReview extends Model
{
    protected $fillable = [
        'reviewer_name',
        'video_title',
        'review_text',
        'youtube_url',
        'image_path',
        'published',
        'permission_confirmed',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'permission_confirmed' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $videoId = self::youtubeVideoIdFromUrl($this->youtube_url);

        return $videoId ? 'https://www.youtube-nocookie.com/embed/'.$videoId : null;
    }

    public static function youtubeVideoIdFromUrl(?string $url): ?string
    {
        if (! is_string($url) || $url === '') {
            return null;
        }

        $parts = parse_url($url);
        if (! is_array($parts) || strtolower($parts['scheme'] ?? '') !== 'https') {
            return null;
        }

        $host = Str::lower(rtrim($parts['host'] ?? '', '.'));
        $path = $parts['path'] ?? '';

        if ($host === 'youtu.be' || $host === 'www.youtu.be') {
            $videoId = trim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            parse_str($parts['query'] ?? '', $query);
            if (isset($query['v']) && is_string($query['v'])) {
                $videoId = $query['v'];
            } elseif (preg_match('~^/(?:embed|shorts|live)/([A-Za-z0-9_-]{11})/?$~', $path, $matches)) {
                $videoId = $matches[1];
            } else {
                return null;
            }
        } else {
            return null;
        }

        return is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId)
            ? $videoId
            : null;
    }
}
