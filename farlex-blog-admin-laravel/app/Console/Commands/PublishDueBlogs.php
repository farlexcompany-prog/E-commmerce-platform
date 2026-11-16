<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PublishDueBlogs extends Command
{
    protected $signature = 'blog:publish-due';

    protected $description = 'Publish draft blogs whose publish_date has arrived';

    public function handle(): int
    {
        // NOW() is evaluated by MySQL, so it matches the times stored in publish_date.
        $due = DB::table('blogs')
            ->where('status', 'draft')
            ->whereNotNull('publish_date')
            ->where('publish_date', '<=', DB::raw('NOW()'))
            ->orderBy('publish_date')
            ->get(['id', 'title']);

        if ($due->isEmpty()) {
            $this->info('Nothing due.');
            return self::SUCCESS;
        }

        foreach ($due as $blog) {
            DB::table('blogs')
                ->where('id', $blog->id)
                ->where('status', 'draft')
                ->update(['status' => 'published', 'published_at' => DB::raw('NOW()')]);

            $this->info("Published #{$blog->id}: {$blog->title}");
        }

        return self::SUCCESS;
    }
}
