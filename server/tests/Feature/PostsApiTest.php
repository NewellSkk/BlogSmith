<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostsApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_all_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(2)->create(['user_id' => $user->id]);
        Post::factory()->count(3)->create(); //Other users' posts

        $response = $this->actingAsSanctum($user)->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'posts' => ['*' => ['id', 'title', 'body', 'user']]
            ])->assertJsonCount(5, 'posts');
    }
}
