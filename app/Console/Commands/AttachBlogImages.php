<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AttachBlogImages extends Command
{
    protected $signature = 'blog:attach-images {--force : Also replace blogs that already have an image}';

    protected $description = 'Attach images in public/uploads/blogs named {blog id}.jpg|png|webp to their blogs';

    public function handle(): int
    {
        $dir  = public_path('uploads/blogs');
        $blogs = DB::table('blogs')->get(['id', 'title', 'image_url']);
        $done = 0;

        foreach ($blogs as $blog) {
            if ($blog->image_url && ! $this->option('force')) {
                continue;
            }

            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                if (is_file("$dir/{$blog->id}.$ext")) {
                    DB::table('blogs')
                        ->where('id', $blog->id)
                        ->update(['image_url' => "uploads/blogs/{$blog->id}.$ext"]);
                    $this->info("#{$blog->id} {$blog->title} -> uploads/blogs/{$blog->id}.$ext");
                    $done++;
                    continue 2;
                }
            }

            $this->warn("#{$blog->id} {$blog->title}: no image file found");
        }

        $this->info("Attached: $done");
        return self::SUCCESS;
    }
}
