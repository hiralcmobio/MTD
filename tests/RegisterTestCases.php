<?php

namespace Tests\testcase;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use \Mockery;
use App\Http\Controllers\Auth\RegisterController;

class RegisterTestCases extends TestCase
{
    use WithFaker;

    /** @test */
    /**
     * Test For Get Sign Up URL from route.
     *
     * @return void
     */
    public function testRegisterUrl()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
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
            $this->assertTrue(
                method_exists($this->mockRegisterController, $method),
                get_class($this->mockRegisterController) . ' does not have method ' . $method
            );
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
        $v = Validator::make($data, $rules);
        $this->assertTrue($v->passes());
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
