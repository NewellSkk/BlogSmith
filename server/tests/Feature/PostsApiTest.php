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
    public function posts_are_returned_with_user_relationship()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAsSanctum($user)->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonPath('posts.0.user.id', $user->id)
            ->assertJsonPath('posts.0.user.name', $user->name);
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


    #[Test]
    public function create_post_requires_authentication()
    {
        $postData = [
            'title' => 'Test Post',
            'body' => 'Test content'
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(401);
    }

    #[Test]
    public function create_post_requires_title_and_body(): void
    {
        $response = $this->actingAsSanctum()->postJson('/api/posts', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'body']);
    }

    #[Test]
    public function authenticated_user_can_update_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['title' => 'Old Title', 'body' => 'Old body', 'user_id' => $user->id]);
        $update = [
            'title' => 'New Title',
            'body' => 'This is the new body'
        ];
        $response = $this->actingAsSanctum($user)
            ->putJson("/api/posts/{$post->id}", $update);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'post' => ['id', 'title', 'body']
            ])
            ->assertJson([
                'message' => 'Post update successful',
                'post' => [
                    'id' => $post->id,
                    'title' => 'New Title',
                    'body' => 'This is the new body',
                ]
            ]);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'New Title',
            'body' => 'This is the new body',
        ]);
    }

    #[Test]
    public function updating_post_validates_required_fields(): void
    {
        $post = Post::factory()->create();
        $response = $this->actingAsSanctum()->putJson("/api/posts/{$post->id}", []);
        $response->assertStatus(422)->assertJsonValidationErrors(['title', 'body']);
    }

    #[Test]
    public function user_cannot_update_others_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $update = [
            'title' => 'New Title',
            'body' => 'This is the new body'
        ];
        $response = $this->actingAsSanctum($user)->putJson("/api/posts/{$post->id}", $update);
        $response->assertForbidden();
    }

    #[Test]
    public function authenticated_user_can_delete_their_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAsSanctum($user)->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Post deleted successfully']);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    #[Test]
    public function user_cannot_delete_others_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $response = $this->actingAsSanctum($user)->deleteJson("/api/posts/{$post->id}");
        $response->assertForbidden();
    }

    #[Test]
    public function delete_post_requires_authentication(): void
    {
        $post = Post::factory()->create();
        $response = $this->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(401);
    }
}
