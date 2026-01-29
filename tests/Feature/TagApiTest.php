<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tag;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class TagApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake authenticated user
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_can_seed_100k_tags()
    {
        Artisan::call('db:seed', ['--class' => 'TagSeeder']);

        $this->assertDatabaseCount('tags', 100_000);
    }

    public function test_tag_list_performance_under_200ms()
    {
        Artisan::call('db:seed', ['--class' => 'TagSeeder']);

        $start = microtime(true);

        $response = $this->getJson('/api/tags?search=tag_9');

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertTrue(
            $duration < 200,
            "Tag search took {$duration}ms, expected < 200ms"
        );
    }

    public function test_guest_cannot_access_tags()
    {
        auth()->logout();

        $this->getJson('/api/tags')
            ->assertStatus(401);
    }

    public function test_can_create_tag()
    {
        $response = $this->postJson('/api/tags', [
            'name' => 'auth',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'auth']);

        $this->assertDatabaseHas('tags', ['name' => 'auth']);
    }

    public function test_tag_name_must_be_unique()
    {
        Tag::create(['name' => 'web']);

        $this->postJson('/api/tags', ['name' => 'web'])
             ->assertStatus(422);
    }

    public function test_can_list_tags()
    {
        Tag::factory()->count(5)->create();

        $this->getJson('/api/tags')
             ->assertStatus(200)
             ->assertJsonStructure(['data']);
    }

    public function test_can_update_tag()
    {
        $tag = Tag::create(['name' => 'old']);

        $this->putJson("/api/tags/{$tag->id}", [
            'name' => 'new',
        ])
        ->assertStatus(200)
        ->assertJsonFragment(['name' => 'new']);
    }

    public function test_can_delete_tag()
    {
        $tag = Tag::create(['name' => 'remove']);

        $this->deleteJson("/api/tags/{$tag->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('tags', ['name' => 'remove']);
    }
}
