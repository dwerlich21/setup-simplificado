<?php

namespace Tests\Feature\Enum;

use Tests\TestCase;

class EnumTest extends TestCase
{
    public function test_list_enums_returns_200(): void
    {
        $response = $this->getJson('/api/v1/enums');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'data',
                'message',
            ]);
    }

    public function test_show_nonexistent_enum_returns_404(): void
    {
        $response = $this->getJson('/api/v1/enums/nonexistent-enum');

        $response->assertStatus(404)
            ->assertJson(['success' => false]);
    }
}
