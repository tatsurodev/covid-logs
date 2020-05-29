<?php

namespace Tests\Feature;

use App\Community;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    // index
    // ownerはfetch可
    /** @test */
    public function owner_can_fetch_communities()
    {
        $this->withExceptionHandling();
        $owner = factory(User::class)->create();
        $community = factory(Community::class)->create([
            'user_id' => $owner->id,
        ]);
        $response = $this->get("/api/communities?api_token={$owner->api_token}");
        // dd(json_decode($response->getContent()));
        $response->assertStatus(200)->assertJson([
            'data' => [[
                'data' => [
                    'id' => $community->id,
                    'user_id' => $owner->id,
                ],
                'links' => [
                    'self' => '/api/communities',
                ]
            ]]
        ]);
        $this->assertCount(1, Community::all());
    }

    // owner以外はfetch不可
    /** @test */
    public function non_owner_cannot_fetch_communities()
    {
        $this->withExceptionHandling();
        $owner = factory(User::class)->create();
        $nonOwner = factory(User::class)->create();
        $community = factory(Community::class)->create([
            'user_id' => $owner->id,
        ]);
        $response = $this->get("/api/communities?api_token={$nonOwner->api_token}");
        // dd(json_decode($response->getContent()));
        $response->assertStatus(403);
    }
}
