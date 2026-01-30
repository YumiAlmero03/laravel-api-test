<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\Tag;
use App\Models\User;

class TagApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_can_list_tags()
    {
        Tag::factory()->count(3)->create();

        $this->getJson('/api/tags')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
    public function test_can_create_tag()
    {
        $response = $this->postJson('/api/tags', [
            'name' => 'new-tag',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'new-tag']);

        $this->assertDatabaseHas('tags', ['name' => 'new-tag']);
    }
    public function test_tag_name_must_be_unique()
    {
        Tag::create(['name' => 'existing-tag']);

        $this->postJson('/api/tags', ['name' => 'existing-tag'])
             ->assertStatus(422);
    }
    public function test_can_update_tag()
    {
        $tag = Tag::create(['name' => 'old-name']);

        $this->putJson("/api/tags/{$tag->id}", [
            'name' => 'updated-name',
        ])
        ->assertStatus(200)
        ->assertJsonFragment(['name' => 'updated-name']);
    }
    public function test_can_delete_tag()
    {
        $tag = Tag::create(['name' => 'to-be-deleted']);

        $this->deleteJson("/api/tags/{$tag->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('tags', ['name' => 'to-be-deleted']);
    }
    public function test_tags_list_is_fast_with_100k_rows()
    {
        $this->seed(\Database\Seeders\PerformanceSeeder::class);

        Sanctum::actingAs(User::factory()->create());

        $start = microtime(true);

        $this->getJson('/api/tags?count=50')
            ->assertOk()
            ->assertJsonStructure(['data']);

        $duration = microtime(true) - $start;

        $this->assertLessThan(
            0.5,
            $duration,
            "Tags endpoint exceeded 500ms: {$duration}s"
        );
    }


}
