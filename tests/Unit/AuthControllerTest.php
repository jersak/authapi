<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * @inheritDoc
     *
     * @return void
     */
    public function setup(): void
    {
        parent::setUp();

        // Run migrations before tests to ensure tables are there.
        Artisan::call('migrate');

        $testuser = new User([
            'name'     => 'testUser',
            'email'    => 'testuser@testemail.com',
            'password' => Hash::make('secret'),
            'token'    => 'token',
        ]);

        $testuser->save();
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function tearDown(): void
    {
        // Reset the migrations after tests
        $testuser = User::where('email', 'testuser@testemail.com')->first();
        $testuser->delete();

        parent::tearDown();
    }

    /**
     * Test if the expected class functions still exist
     *
     * @return void
     */
    public function testFunctionsExistence()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\AuthController::class, 'authenticate'));
        $this->assertTrue(method_exists(\App\Http\Controllers\AuthController::class, 'createJwtToken'));
    }

    /**
     * Test for successful authentication
     *
     * @return void
     */
    public function testSuccessfulAuthentication()
    {
        $query             = User::where('email', 'testuser@testemail.com')->first()->toArray();
        $query['password'] = 'secret';

        $request = new Request;

        $request->replace($query);
        $result = app('App\Http\Controllers\AuthController')->authenticate($request);

        $data = $result->getData();

        $this->assertTrue($result->status() == Response::HTTP_OK);
        $this->assertEmpty(!($data->token));

    }

    /**
     * Test for an authentication attempt with wrong password
     *
     * @return void
     */
    public function testWrongPassAuthentication()
    {
        $this->expectException(NotFoundHttpException::class);

        $query             = User::where('email', 'testuser@testemail.com')->first()->toArray();
        $query['password'] = 'whatever';

        $request = new Request;

        $request->replace($query);
        $result = app('App\Http\Controllers\AuthController')->authenticate($request);

    }

    /**
     * Test for an authentication attempt with invalid user
     *
     * @return void
     */
    public function testInvalidUserAuthentication()
    {
        $this->expectException(NotFoundHttpException::class);

        $query = [
            'name'     => 'whateverUser',
            'email'    => 'whateverUser@whatevermail.com',
            'password' => Hash::make('secret'),
            'token'    => 'token',
        ];

        $request = new Request;

        $request->replace($query);
        $result = app('App\Http\Controllers\AuthController')->authenticate($request);

    }
}
