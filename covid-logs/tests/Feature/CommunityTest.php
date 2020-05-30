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

    // auth user, $communityは$userとrelationあり
    protected $user, $anotherUser, $community;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->anotherUser = factory(User::class)->create();
        $this->community = factory(Community::class)->create([
            'user_id' => $this->user->id
        ]);
    }

    // index
    // ownerはfetch可
    /** @test */
    public function owner_can_fetch_communities()
    {
        $this->withExceptionHandling();
        $response = $this->get("/api/communities?api_token={$this->user->api_token}");
        // dd(json_decode($response->getContent()));
        $response->assertStatus(200)->assertJson([
            'data' => [[
                'data' => [
                    'id' => $this->community->id,
                    'user_id' => $this->user->id,
                ],
                'links' => [
                    'self' => '/api/communities',
                ]
            ]]
        ]);
        // dd($response->json('data'));
        $this->assertEquals(1, count($response->json('data')));
    }

    // owner以外はfetch不可
    /** @test */
    public function non_owner_cannot_fetch_communities()
    {
        $this->withExceptionHandling();
        // api_tokenの所有者のdataが返ってくる
        $response = $this->actingAs($this->anotherUser)->get("/api/communities?api_token={$this->anotherUser->api_token}");
        // dd(json_decode($response->getContent()));

        // anotherUserはcommunity未作成なのでdataはempty
        $response->assertStatus(200)->assertExactJson([
            'data' => []
        ]);
    }
}
