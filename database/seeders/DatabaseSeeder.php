<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(5)
        //     ->has(Post::factory(rand(1,5))->has(Comment::factory()))
        //     ->create();

        User::factory(rand(2,5))->create()->each(
            function ($user) {
                Post::factory(rand(1, 6))->create([
                    'user_id' => $user->id
                ])->each(function ($post) use ($user) {
                    Comment::factory(rand(1, 5))->create([
                        'post_id' => $post->id,
                        'user_id' => $user->id
                    ]);
                });
            }
        );
    }
}
