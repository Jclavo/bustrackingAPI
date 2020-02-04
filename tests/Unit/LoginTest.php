<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class LoginTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function test_register_repassword_is_required()
    {
        // Generate an user object
        $user = factory(User::class)->make(['c_password' => '']);
              
        //Submit post request to create an user endpoint
        $response = $this->post('api/register', $user->toArray());
        
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The c password field is required.']);
    }
       
    public function test_register_email_is_required()
    {
        // Generate an user object
        $user = factory(User::class)->make(['email' => '']);
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/register', $user->toArray());
        
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The email field is required.']);
    }
    
    public function test_register_password_is_required()
    {
        // Generate an user object
        $user = factory(User::class)->make(['password' => '']);
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/register', $user->toArray());
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The password field is required.']);
    }
    
    public function test__register_name_is_required()
    {
        // Generate an user object
        $user = factory(User::class)->make(['name' => '']);
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/register', $user->toArray());
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The name field is required.']);
    }
    
    public function test_register_password_repassword_are_not_equal()
    {
        // Generate an user object
        $user = factory(User::class)->make(['c_password' => encrypt('secret')]);
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/register', $user->toArray());
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The c password and password must match.']);
    }
    
    public function test_register_an_user()
    {
        // Generate an user object
        $user = factory(User::class)->make();
        
        $user->c_password = $user->password;
        //Submit post request to create an user endpoint
        $response = $this->post('api/register', $user->toArray());
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(200);
        
        // Verify values in response
        $response->assertJson(['success' => true]);
        $response->assertJson(['message' => 'User created successfully.']);
        //$response->assertJson(['data' => $user->toArray()]);
    }
    
    
    public function test_login_email_is_required()
    {
        // Generate an user object
        $user = factory(User::class)->make(['email' => '']);
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/login', $user->toArray());
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The email field is required.']);
    }
    
    public function test_login_email_fortmat_is_validated()
    {
        // Generate an user object
        $user = factory(User::class)->make(['email' => 'myemail.com']);
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/login', $user->toArray());
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The email must be a valid email address.']);
    }
    
    
    public function test_login_password_is_required()
    {
        // Generate an user object
        $user = factory(User::class)->make(['password' => '']);
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/login', $user->toArray());
        
        //Verify in the database
        $this->assertDatabaseMissing('users', $user->toArray());
        
        // Verify status 200
        $response->assertStatus(404);
        
        // Verify values in response
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'The password field is required.']);
    }
    
    public function test_login_user_correctly()
    {
        // Generate an user object
        $user = factory(User::class)->create();
        
        $this->assertDatabaseHas('users', $user->toArray());
        
        $user->password = 'secret';
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/login', $user->toArray());
        
        //Verify in the database
        
        // Verify status 200
        $response->assertStatus(200);
        
        // Verify values in response
        $response->assertJson(['success' => true]);
        $response->assertJson(['message' => 'User logged successfully.']);
        
    }
    
    
    public function test_login_response_api_token()
    {
        // Generate an user object
        $user = factory(User::class)->create();
        
        $this->assertDatabaseHas('users', $user->toArray());
        
        $user->password = 'secret';
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/login', $user->toArray());
        
        //Verify in the database
             
        $this->assertNotEmpty(json_decode($response->content(),true)['result']['api_token']);
        
    }
    
    public function test_unauthenticated_user()
    {
        // Generate an user object
        $user = factory(User::class)->create();
       
        $user->password = 'secret';
        
        //Submit post request to create an user endpoint
        $this->post('api/login', $user->toArray());
        
        //Verify in the database
        
        $response = $this->get('api/getUserInformation');
              
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => 'Unauthenticated.']);
        
    }
    
    
    public function test_only_user_logged()
    {
        // Generate an user object
        $user = factory(User::class)->create();
        
        $user->password = 'secret';
        
        //Submit post request to create an user endpoint
        $response = $this->post('api/login', $user->toArray());
        
        //Verify in the database
        $this->assertNotEmpty(json_decode($response->content(),true)['result']['api_token']);
        
        /*$response = $this->get('api/getUserInformation', [], [], [
            'headers' => [
                'HTTP_AUTHORIZATION' => 'bearer ' . json_decode($response->content(),true)['result']['api_token']
                //'CONTENT_TYPE' => 'application/ld+json',
                //'HTTP_ACCEPT' => 'application/ld+json'
            ]]);*/
        
        $response = 
        
        $this->withHeaders(['Authorization' => 'Bearer '. json_decode($response->content(),true)['result']['api_token']])
              ->get('api/getUserInformation');
        
        
        $response->assertJson(['success' => true]);
        $response->assertJson(['message' => 'User information gotten successfully.']);
        //$response->assertJson(['result' => $user->toArray()]);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
