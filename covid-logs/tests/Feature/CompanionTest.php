<?php

namespace Tests\Feature;

use App\Companion;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CompanionTest extends TestCase
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
    public function owner_can_fetch_companions()
    {
        $this->withExceptionHandling();
        $companion = factory(Companion::class)->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->get("/api/companions?api_token={$this->user->api_token}");
        // dd(json_decode($response->getContent()));
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'data' => [[
                'data' => [
                    'id' => $companion->id,
                    'user_id' => $this->user->id,
                ],
                'links' => [
                    'self' => '/companions',
                ]
            ]]
        ]);
        // dd($response->json('data'));
        $this->assertEquals(1, count($response->json('data')));
    }

    // owner以外はfetch不可
    /** @test */
    public function non_owner_cannot_fetch_companions()
    {
        $this->withExceptionHandling();
        // api_tokenの所有者のdataが返ってくる
        $response = $this->actingAs($this->user)->get("/api/companions?api_token={$this->user->api_token}");
        // dd(json_decode($response->getContent()));

        // $userはcompanion未作成なのでdataはempty
        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'data' => []
        ]);
    }

    // store
    // auth user store可
    /** @test */
    public function auth_user_can_store_companion()
    {
        $this->withExceptionHandling();
        $response = $this->post(
            '/api/companions',
            $this->data(),
        );
        // dd(json_decode($response->getContent()));
        $storedData = Companion::first();
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
    public function non_auth_user_cannot_store_companion()
    {
        $response = $this->post(
            '/api/companionies',
            // token削除
            array_merge($this->data(), ['api_token' => '']),
        );
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    // update
    // ownerはupdate可
    /** @test */
    public function owner_can_update_companions()
    {
        $this->withExceptionHandling();
        $companion = factory(Companion::class)->create([
            'user_id' => $this->user->id
        ]);
        // patchは一部、putは全部更新
        $response = $this->patch(
            "/api/companions/{$companion->id}",
            $this->data()
        );
        $storedData = $companion->fresh();
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
    public function non_owner_cannot_update_companions()
    {
        $companion = factory(Companion::class)->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->patch(
            "/api/companions/{$companion->id}",
            array_merge(
                $this->data(),
                ['api_token' => $this->anotherUser->api_token]
            )
        );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    // show
    // ownerはshow可
    /** @test */
    public function owner_can_show_companion()
    {
        $companion = factory(Companion::class)->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->get("/api/companions/{$companion->id}?api_token={$this->user->api_token}");
        // dd(json_decode($response->getContent()));
        $response->assertJson([
            'data' => [
                'id' => $companion->id,
                'name' => $companion->name,
                'user_id' => $companion->user_id,
            ]
        ]);
    }

    // owner以外はshow不可、403
    /** @test */
    public function non_owner_cannot_show_companion()
    {
        $companion = factory(Companion::class)->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->get("/api/companions/{$companion->id}?api_token={$this->anotherUser->api_token}");
        // 401 unauthorized, 403 forbidden, 404 not found
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    // destroy
    // ownerはdelete可
    /** @test */
    public function owner_can_destroy_companion()
    {
        $companion = factory(Companion::class)->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->delete(
            "/api/companions/{$companion->id}",
            ['api_token' => $this->user->api_token]
        );
        $this->assertCount(0, Companion::all());
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    // owner以外はdelete不可、403
    /** @test */
    public function non_owner_cannot_destroy_companion()
    {
        $companion = factory(Companion::class)->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->delete(
            "/api/companions/{$companion->id}",
            ['api_token' => $this->anotherUser->api_token]
        );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    // store,update用のtoken付きdata
    private function data()
    {
        return [
            'name' => 'Yamada Hanako',
            'user_id' => $this->user->id,
            'api_token' => $this->user->api_token,
        ];
    }
}
