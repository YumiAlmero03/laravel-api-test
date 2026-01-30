<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\User;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_can_create_translation()
    {
        $locale = Locale::factory()->create();
        $tag = Tag::factory()->create();

        $this->postJson('/api/translations', [
            'locale_id' => $locale->id,
            'key' => 'home.title',
            'value' => 'Welcome',
            'tags' => [$tag->id],
        ])->assertStatus(201);
    }

    public function test_can_search_translation_by_tag()
    {
        Translation::factory()->count(5)->create();

        $this->getJson('/api/translations?key=app')
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function translation_seeder_populates_database(): void
    {
        $this->seed();

        $this->assertGreaterThan(
            100_000,
            Translation::count(),
            'Expected seeded translations to exceed 100k rows'
        );
    }

    public function it_exports_translations_as_json(): void
    {
        $this->seed();

        $locale = Locale::first();

        $response = $this->getJson("/api/translations/export?locale={$locale->code}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                '*' => [],
            ]);

        $this->assertNotEmpty($response->json());
    }

    public function export_endpoint_handles_large_dataset(): void
    {
        $this->seed();

        $locale = Locale::first();

        $start = microtime(true);

        $this->getJson("/api/translations/export?locale={$locale->code}")
            ->assertOk();

        $duration = microtime(true) - $start;

        $this->assertLessThan(
            0.5,
            $duration,
            "Export endpoint exceeded expected performance threshold"
        );
    }

    public function it_can_search_by_value(): void
    {
        $this->seed();

        $response = $this->getJson('/api/translations/search?query=account');

        $response
            ->assertOk()
            ->assertJsonCount(10); // paginated
    }
}
