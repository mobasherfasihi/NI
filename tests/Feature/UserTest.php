<?php

namespace Tests\Feature;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\CreatesApplication;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var \App\Http\Requests\LoginRequest */
    private $rules;
    
    /** @var \Illuminate\Validation\Validator */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new LoginRequest())->rules();
    }

    public function validationProvider()
    {
        /* WithFaker trait doesn't work in the dataProvider */
        $faker = Factory::create( Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_email_is_provided' => [
                'passed' => false,
                'data' => [
                    'password' => $faker->password()
                ]
            ],
            'request_should_fail_when_no_password_is_provided' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email()
                ]
            ],
            'request_should_fail_when_email_is_invalid_format' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->word()
                ]
            ],
            'request_should_fail_when_password_is_less_than_6_characters' => [
                'passed' => false,
                'data' => [
                    'password' => $faker->password($minLength = 2, $maxLength = 4)
                ]
            ],
            'request_should_pass_when_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'email' => $faker->email(),
                    'password' => $faker->password($minLength = 6, $maxLength = 10)
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $this->assertEquals(
            $shouldPass, 
            $this->validate($mockedRequestData)
        );
    }

    protected function validate($mockedRequestData)
    {
        return $this->validator
            ->make($mockedRequestData, $this->rules)
            ->passes();
    }

    /**
     * @test
     */
    public function it_should_allow_user_to_login() 
    {
        $user = User::factory()->create([
            'email' => 'mobasher@gmail.com',
            'password' => bcrypt('password')
        ]);

        $loginData = ['email' => 'mobasher@gmail.com', 'password' => 'password'];

        $response = $this->json('POST', 'api/auth', $loginData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "data" => [
                    'token',
                    'user' => [
                        'name',
                    ]
                ],
                "success",
                "message"
             ]);

        $this->assertAuthenticated();     
    }

    /**
     * @test
     */
    public function it_should_prevent_user_to_login_with_invalid_email() 
    {
        $user = User::factory()->create();

        $response = $this->json('POST', 'api/auth', ['email' => 'new'.$user->email, 'password' => 'password'])
            ->assertUnauthorized()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function it_should_prevent_user_to_login_with_invalid_password() 
    {
        $user = User::factory()->create();

        $response = $this->json('POST', 'api/auth', ['email' => $user->email, 'password' => 'new-password'])
            ->assertUnauthorized()
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function it_should_return_auth_user_details() 
    {
        $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(User::factory()->create(), ['api']);
        
        $response = $this->json('GET', 'api/user');
        
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['data' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]]);
    }
}
