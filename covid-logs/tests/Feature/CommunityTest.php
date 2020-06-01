<?php

namespace Tests\Feature;

use App\Community;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
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
            'user_id' => $this->user->id
        ]);
        $response = $this->get("/api/communities?api_token={$this->user->api_token}");
        // dd(json_decode($response->getContent()));
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'data' => [[
                'data' => [
                    'id' => $community->id,
                    'user_id' => $this->user->id,
                ],
                'links' => [
                    'self' => '/communities',
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
        $response = $this->actingAs($this->user)->get("/api/communities?api_token={$this->user->api_token}");
        // dd(json_decode($response->getContent()));

        // $userはcommunity未作成なのでdataはempty
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'data' => []
        ]);
    }

    // store
    // auth user store可
    /** @test */
    public function auth_user_can_store_community()
    {
        $this->withExceptionHandling();
        $response = $this->post(
            '/api/communities',
            $this->data(),
        );
        // dd(json_decode($response->getContent()));
        $storedData = Community::first();
        $this->assertEquals($this->data()['name'], $storedData->name);
        $this->assertEquals($this->data()['user_id'], $storedData->user_id);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'data' => [
                'id' => $storedData->id,
            ],
            'links' => [
                'self' => $storedData->path(),
            ]
        ]);
    }

    // non-auth user store不可、存在しないtokenでpostすると405, MethodNotAllowedHttpExceptionが返ってくる
    /** @test */
    public function non_auth_user_cannot_store_community()
    {
        $response = $this->post(
            '/api/communityies',
            // token削除
            array_merge($this->data(), ['api_token' => '']),
        );
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    // update
    // ownerはupdate可
    /** @test */
    public function owner_can_update_communities()
    {
        $this->withExceptionHandling();
        $community = factory(Community::class)->create([
            'user_id' => $this->user->id
        ]);
        // patchは一部、putは全部更新
        $response = $this->patch(
            "/api/communities/{$community->id}",
            $this->data()
        );
        $storedData = $community->fresh();
        // dd($storedData);
        $this->assertEquals($this->data()['name'], $storedData->name);
        $this->assertEquals($this->data()['user_id'], $storedData->user_id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $storedData->id,
            ],
            'links' => [
                'self' => $storedData->path(),
            ],
        ]);
    }

    // owner以外はupdate不可, 403
    /** @test */
    public function non_owner_cannot_update_communities()
    {
        $community = factory(Community::class)->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->patch(
            "/api/communities/{$community->id}",
            array_merge(
                $this->data(),
                ['api_token' => $this->anotherUser->api_token]
            )
        );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    // store,update用のtoken付きdata
    private function data()
    {
        return [
            'name' => 'football circle',
            'user_id' => $this->user->id,
            'api_token' => $this->user->api_token,
        ];
    }
}
