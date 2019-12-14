<?php

namespace Tests\Controllers\Auth;

use Auth;
use App\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{

    /**
     * Test For Get Login Page.
     *
     * @return void
     */
    /** @test */
    public function testGetLoginPage()
    {
        $response = $this->call('GET', '/login');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test Post Method While Log In.
     *
     * @return void
     */
    /** @test */
    public function testLoginPost()
    {
        $response = $this->call('POST', '/login', [
            'email' => 'admin@gmail.com',
            'password' => 'adminadmin',
            '_token' => csrf_token()
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('/home');
    }

    /**
     * Test Log In When Pass Incorrect username or password.
     *
     * @return void
     */
    public function testLoginFalse()
    {

        $response = $this->post('/login', [
            'email' => 'user@ad.com',
            'password' => 'incorrectpass',
        ]);

        $response->assertSessionHasErrors();
    }

    /**
     * Test Log In When User Login Successfully.
     *
     * @return void
     */
    public function testUserLoginSuccessfully()
    {

        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'adminadmin'          
        ]);
    
        $response->assertRedirect('/home');
        $response->assertStatus(302);
        $this->assertTrue(Auth::check());
    }
}
