<?php

namespace Tests\Feature;

use App\Log;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogTest extends TestCase
{
    use RefreshDatabase;

    // index
    // 認証済ユーザーはfetch可
    /** @test */
    public function auth_user_can_fetch_logs()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();
        $logs = factory(Log::class)->create();
        $response = $this->get("/api/logs?api_token={$user->api_token}");
        $response->assertStatus(200);
        $this->assertCount(1, Log::all());
    }

    // 非認証ユーザーはfetch不可、login画面へredirect
    /** @test */
    public function non_auth_user_can_fetch_logs()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();
        $logs = factory(Log::class)->create();
        $response = $this->get("/api/logs?api_token=");
        $response->assertRedirect('/login');
    }
}
