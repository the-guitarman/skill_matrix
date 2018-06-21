<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Config};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use App\Libs\Ldap;
use App\Models\User;

class SessionControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testLoginForm()
    {
        $this
            ->get(route('login'))
            ->assertStatus(200)
            ->assertSee('<h1>LDAP-Login</h1>')
        ;
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testLoginWithWrongCredentials()
    {
        $allUserCount = User::count();

        $mock = \Mockery::mock('alias:'.Ldap::class);
        $mock->shouldReceive('authenticate')
            ->once()
            ->withArgs([null, null])
            ->andReturn(false);
        $mock->shouldReceive('get_config')
            ->andReturn(Config::get('ldap.default'));

        $this->app->instance(Ldap::class, $mock);




        $response = $this->post(route('login_create'), [
            'auth' => [],
            'remember' => 1,
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'))
            ->assertSessionHas('flash_error', "Das Login und/oder das Password ist/sind falsch.")
            ->assertSessionHasErrors([
                'login' => "Das Login und/oder das Password ist/sind falsch.",
                'auth.login' => "Bitte geben Sie ihren Benutzername ein.",
                'auth.password' => "Bitte geben Sie ihr Passwort ein.",
            ])
        ;




        $mock->shouldReceive('authenticate')
            ->once()
            ->withArgs(['tesla', 'wrong-password'])
            ->andReturn(false);

        $response = $this->post(route('login_create'), [
            'auth' => [
                'login' => 'tesla',
                'password' => 'wrong-password',
            ],
            'remember' => 1,
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('login'))
            ->assertSessionHas('flash_error', "Das Login und/oder das Password ist/sind falsch.")
            ->assertSessionHasErrors([
                'login' => "Das Login und/oder das Password ist/sind falsch.",
            ])
        ;

        $this->assertEquals($allUserCount, User::count());
    }


    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testLoginWithRightCredentials()
    {
        $allUserCount = User::count();

        $mock = \Mockery::mock('alias:'.Ldap::class);
        $mock->shouldReceive('authenticate')
            ->once()
            ->withArgs(['new-user', 'right-password'])
            ->andReturn(true);
        $mock->shouldReceive('get_user_info')
            ->once()
            ->withArgs(['new-user'])
            ->andReturn([['cn' => ['New User']]]);

        app()->instance(Ldap::class, $mock);

        $response = $this->post(route('login_create'), [
            'auth' => [
                'login' => 'new-user',
                'password' => 'right-password',
            ],
            'remember' => 1,
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('root'))
            ->assertSessionHas('flash_notice', 'Sie sind nun eingeloggt.')
        ;

        $this->assertEquals($allUserCount + 1, User::count());
    }

    public function testLogout()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->from(route('root'))
            ->delete(route('logout'))
            ->assertStatus(302)
            ->assertRedirect(route('login'))
            ->assertSessionHas('flash_notice', 'Sie sind nun ausgeloggt.')
        ;
    }
}
