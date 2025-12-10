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

    #[Test]
    public function authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $postData = [
            'title' => 'Test Title',
            'body' => 'This is the test body.',
        ];
        $response = $this->actingAsSanctum($user)->postJson('/api/posts', $postData);
        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'message', 'data' => ['id', 'title', 'body']])
            ->assertJson([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => [
                    'title' => 'Test Title',
                    'body' => 'This is the test body.',
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Title',
            'body' => 'This is the test body.',
            'user_id' => $user->id,
        ]);
    }
}
