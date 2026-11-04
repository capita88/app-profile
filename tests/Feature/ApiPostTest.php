<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_published_posts(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Post Terbit',
            'slug' => 'post-terbit',
            'summary' => 'Ringkasan post',
            'content' => '<p>Isi konten terbit</p>',
            'status' => PostStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Post Draft',
            'slug' => 'post-draft',
            'summary' => 'Ringkasan draft',
            'content' => '<p>Isi konten draft</p>',
            'status' => PostStatus::Draft,
        ]);

        $response = $this->getJson('/api/v1/posts');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'post-terbit');
    }

    public function test_can_get_single_post_and_increments_views(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Detail Post',
            'slug' => 'detail-post',
            'summary' => 'Ringkasan',
            'content' => '<p>Konten detail</p>',
            'status' => PostStatus::Published,
            'published_at' => now()->subDay(),
            'view_count' => 5,
        ]);

        $response = $this->getJson('/api/v1/posts/detail-post');

        $response->assertOk()
            ->assertJsonPath('data.title', 'Detail Post')
            ->assertJsonPath('data.view_count', 6);

        $this->assertEquals(6, $post->fresh()->view_count);
    }
}
