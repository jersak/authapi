<?php

namespace Tests\Unit;

use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * Test if the expected class functions still exist
     *
     * @return void
     */
    public function testFunctionsExistence()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserController::class, 'create'));
    }

    /**
     * Test for valid user creation
     *
     * @return void
     */
    public function testCreateValidUser()
    {
        $faker = Faker::create();

        $request = new Request;

        $query = [
            'name'     => $faker->name(),
            'email'    => $faker->email(),
            'password' => 'secret',
        ];

        $request->replace($query);
        $result = app('App\Http\Controllers\UserController')->create($request);

        $data = $result->getData();

        $this->assertTrue($result->status() == Response::HTTP_OK);
        $this->assertEmpty(!($data));
    }

    /**
     * Test user creation with missing name
     *
     * @return void
     */
    public function testMissingNameUser()
    {
        $this->expectException(UnprocessableEntityHttpException::class);

        $faker = Faker::create();

        $request = new Request;

        $query = [
            'email'    => $faker->email(),
            'password' => 'secret',
        ];

        $request->replace($query);
        $result = app('App\Http\Controllers\UserController')->create($request);
    }

    /**
     * Test user creation with missing email
     *
     * @return void
     */
    public function testMissingEmailUser()
    {
        $this->expectException(UnprocessableEntityHttpException::class);

        $faker = Faker::create();

        $request = new Request;

        $query = [
            'name'     => $faker->name(),
            'password' => 'secret',
        ];

        $request->replace($query);
        $result = app('App\Http\Controllers\UserController')->create($request);
    }

    /**
     * Test user creation with missing password
     *
     * @return void
     */
    public function testMissingPasswordUser()
    {
        $this->expectException(UnprocessableEntityHttpException::class);

        $faker = Faker::create();

        $request = new Request;

        $query = [
            'name'  => $faker->name(),
            'email' => $faker->email(),
        ];

        $request->replace($query);
        $result = app('App\Http\Controllers\UserController')->create($request);
    }

}
