<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $videos = [
        [
            'video_id' => 'S0FoTOvtQm4',
            'title' => 'Customer Testimonial 3 | Farlex Pharmaceuticals Pvt. Ltd. - Best PCD Company in Panchkula',
            'position' => 0,
        ],
        [
            'video_id' => 'pixet2YkAmI',
            'title' => 'Customer Testimonial 4 | Farlex Pharmaceuticals Pvt. Ltd. - Best PCD Company in Panchkula',
            'position' => 1,
        ],
        [
            'video_id' => 'jQ5QvNL8mvU',
            'title' => 'Best PCD Pharma Franchise Company in Panchkula | Farlex Pharmaceuticals',
            'position' => 2,
        ],
    ];

    public function up(): void
    {
        Schema::table('cms_reviews', function (Blueprint $table) {
            $table->string('video_title', 255)->nullable();
        });

        foreach ($this->videos as $video) {
            $url = 'https://www.youtube.com/watch?v='.$video['video_id'];
            if (DB::table('cms_reviews')->where('youtube_url', $url)->exists()) {
                continue;
            }

            DB::table('cms_reviews')->insert([
                'reviewer_name' => 'Farlex Pharma',
                'video_title' => $video['title'],
                'review_text' => null,
                'youtube_url' => $url,
                'image_path' => null,
                'published' => true,
                'permission_confirmed' => true,
                'position' => $video['position'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        foreach ($this->videos as $video) {
            DB::table('cms_reviews')
                ->where('youtube_url', 'https://www.youtube.com/watch?v='.$video['video_id'])
                ->where('video_title', $video['title'])
                ->where('reviewer_name', 'Farlex Pharma')
                ->delete();
        }

        Schema::table('cms_reviews', function (Blueprint $table) {
            $table->dropColumn('video_title');
        });
    }
};
