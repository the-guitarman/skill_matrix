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

    public function testCopyright()
    {
        Carbon::setTestNow(Carbon::create(2020, 1, 1));

        $class = new \ReflectionClass('\App\Providers\AppServiceProvider');
        $method = $class->getMethod('copyright');
        $method->setAccessible(true);
        $asp = new \App\Providers\AppServiceProvider($this->app);

        $copyright = $method->invokeArgs($asp, []);
        $this->assertEquals('Copyright 2018 - 2020', $copyright);
    }

}
