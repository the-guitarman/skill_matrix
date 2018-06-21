<?php
/*
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
*/


namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\{
    Artisan, Config, DB, Hash
};
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    protected static $migrationsRun = false;

    //use CreatesApplication;
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        //Load environment
        $app->loadEnvironmentFrom('.env.'.env('APP_ENV'));

        $app->make(Kernel::class)->bootstrap();

        //Speeds up bcrypt password hashing
        Hash::setRounds(4);

        return $app;
    }

    public static function setUpBeforeClass()
    {

    }

    public static function tearDownAfterClass()
    {

    }

    public function setUp()
    {
        $this->showCurrentTestClassAndMethodNames(false);

        parent::setUp();

        if (!self::$migrationsRun) {
            Artisan::call('migrate:fresh'); //, ['--env' => 'testing', '--database' => 'mysql']);
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--database' => 'mysql']);

            self::$migrationsRun = true;
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }

    /**
     * Set the URL of the previous request.
     *
     * @param  string  $url
     * @return $this
     *
     */
    public function from(string $url)
    {
        $this->app['session']->setPreviousUrl($url);

        return $this;
    }

    public function responseHasTag(TestResponse $response, string $tagName, array $tagAttributes = []) {
        $tagName = preg_quote($tagName);
        $content = $response->getContent();
        $this->assertRegExp("/<$tagName/", $content);
        foreach($tagAttributes as $key => $value) {
            $tag = ["<$tagName"];
            $tag[] = preg_quote($key).'="'.preg_quote($value).'"';
            $regex = '/'.implode('.*?', $tag).'.*?>/';
            $this->assertRegExp($regex, $content);
        }
        return $response;
    }

    public function loginRequired(string $method, string $route, array $params = [])
    {
        $this->from(route('login'))
            ->$method(route($route, $params))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Prints the errors from the session store.
     *
     * @return $this
     */
    public function print_session_errors()
    {
        $errors = $this->app['session.store']->get('errors');
        if (!empty($errors)) {
            print_r($errors->getBag('default'));
        }

        return $this;
    }

    /*
    protected function mandantenAndUserSeedsCount()
    {
        // Seeds-Mandanten/Users ...
        $count = config('seeds.users') ? count(config('seeds.users')) : 0;
        // ... +1 Demo Mandant/User
        return $count + 1;
    }
    */

    protected function truncateDbTable(string $tableName)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($tableName)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    protected function showCurrentTestClassAndMethodNames($showIt = false)
    {
        if ($showIt) {
            print_r(" ".get_class($this).'#'.$this->getName()."\n");
        }
    }
}
