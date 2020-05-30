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

    // auth user
    protected $user, $anotherUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->anotherUser = factory(User::class)->create();
    }

    // index
    // ownerはfetch可
    /** @test */
    public function owner_can_fetch_communities()
    {
        $this->withExceptionHandling();
        $community = factory(Community::class)->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->get("/api/communities?api_token={$this->user->api_token}");
        // dd(json_decode($response->getContent()));
        $response->assertStatus(200)->assertJson([
            'data' => [[
                'data' => [
                    'id' => $community->id,
                    'user_id' => $this->user->id,
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
        $community = factory(Community::class)->create([
            'user_id' => $this->user->id,
        ]);
        // api_tokenの所有者のdataが返ってくる
        $response = $this->actingAs($this->anotherUser)->get("/api/communities?api_token={$this->anotherUser->api_token}");
        // dd(json_decode($response->getContent()));

        // $ownerResponse = $this->actingAs($owner)->get("/api/communities?api_token={$owner->api_token}");
        // $nonOwnerResponse = $this->actingAs($owner)->get("/api/communities?api_token={$nonOwner->api_token}");
        // dd('ownerResponse', json_decode($ownerResponse->getContent()), 'nonOwnerResponse', json_decode($nonOwnerResponse->getContent()));

        $response->assertStatus(200)->assertExactJson([
            'data' => []
        ]);
    }
}
