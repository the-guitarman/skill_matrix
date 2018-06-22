<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\{Config};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Models\{User};
use Mockery;

class AppServiceProviderTest extends TestCase
{
    public function testAppName()
    {
        $user = factory(User::class)->create();

        $this->assertNotEmpty(Config::get('app.name'));

        $response = 
            $this->actingAs($user)
                ->from(route('login'))
                ->get('/')
                ->assertStatus(200)
                ->assertSee(Config::get('app.name'))
        ;
    }
/*
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * /
    public function testCopyright()
    {
        //Carbon::setTestNow(Carbon::create(2020, 1, 1));
/*
        $mock = \Mockery::mock('alias:'.Carbon::class);
        $mock->shouldReceive('now')
            ->once()
            ->andReturn(Carbon::create(2020, 1, 1));
        $this->app->instance(Carbon::class, $mock);
* /
        $user = factory(User::class)->create();

        $response = 
            $this->actingAs($user)
                ->from(route('login'))
                ->get('/')
                ->assertStatus(200)
                ->assertSee('Copyright 2018 - 2020')
        ;
    }
*/
}
