<?php

namespace Tests\Controllers\Auth;

use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use App\Http\Controllers\Auth\RegisterController;
use Tests\DuskTestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTestCases extends DuskTestCase
{
    use WithFaker,RefreshDatabase;

    public $mockUser;
    public $mockValidator;
    public $mockLoginController;

    /**
     * Setup test environment
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->app['validator'];
        $this->mockUser = Mockery::mock(User::class)->makePartial();
        $this->mockRegisterController = Mockery::mock(RegisterController::class)->makePartial();
    }

    /**
     * Clear test environment before start test
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Check Register route exist.
     *
     * @test
     */
    public function test_register_route_is_exist()
    {
        $response = $this->call('GET', '/register');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Check for method exists or not.
     *
     * @test
     */
    public function method_exists()
    {
        //get RegisterController
        $this->mockRegisterController = Mockery::mock(RegisterController::class);

        //get lists of methodss
        $methodsToCheck = [
            'validator',
            'create',
        ];

        //check if methods are exists or not
        foreach ($methodsToCheck as $method) {
            $this->checkMethodExist($this->mockRegisterController, $method);
        }
    }

    /**
     * Test User details validate.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function testRegisterSuccessfully()
    {
        //generate request for register
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        //validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        //validate request data
        $validate = $this->mockValidator->make($data, $rules);
        $this->assertTrue($validate->passes());
    }

    /**
     * Check Email id exist in the system.
     *
     * @test
     */
//    public function test_email_exists_in_system()
//    {
//        $this->assertDatabaseHas('users', [
//            'email' => 'hiral@admin.com'
//        ]);
//    }

    /**
     * Check if email id not exist in the system.
     *
     * @test
     */
    public function test_email_not_exists_in_system()
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'notexistemail@gmail.com'
        ]);
    }

    /**
     * Check if password and confirmPassword not match.
     *
     * @test
     */
    public function test_password_not_match_with_confirm_password()
    {
        $password = $this->faker->password();
        $confirmPassword = $this->faker->password();

        $this->assertNotEquals($password, $confirmPassword);
    }

    /**
     * Check if Password and confirm password are match.
     *
     * @test
     */
    public function test_password_match_with_confirm_password()
    {
        $password = $this->faker->password();
        $confirmPassword = $password;

        $this->assertEquals($password, $confirmPassword);
    }

    /**
     * Check if name is string or not.
     *
     * @test
     */
    public function test_name_is_string()
    {
        $this->assertIsString($this->faker->name());
    }

    /**
     * Test User registered successfully.
     *
     * @return \App\User
     */
    public function testUserRegisterSuccessfully()
    {
        //generate request for register
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();
        $requestData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        //save registered data
        $response = $this->post('/register', $requestData);
        $response->assertStatus(302);
    }


}
