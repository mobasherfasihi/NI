<?php

namespace Tests\Feature;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use ProductData;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var \App\Http\Requests\ProductRequest */
    private $rules;
    
    /** @var \Illuminate\Validation\Validator */
    private $validator;

    private $products;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new ProductRequest())->rules();

        $this->products = Product::factory()->count(24)->create();
    }

    public function validationProvider()
    {
        /* WithFaker trait doesn't work in the dataProvider */
        $faker = Factory::create( Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_product_name_is_provided' => [
                'passed' => false,
                'data' => []
            ],
            'request_should_fail_when_product_name_is_not_string' => [
                'passed' => false,
                'data' => [
                    'name' => 1234
                ]
            ],
            'request_should_fail_when_product_name_is_less_than_3_characters' => [
                'passed' => false,
                'data' => [
                    'name' => 'my'
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
    public function it_should_return_all_products_in_paginated_list() 
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(
            User::factory()->create(),
            ['api']
        );

        $response = $this->json('GET', 'api/products');
        
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(10, 'data.data')
            ->assertJsonFragment(['total' => 24]);
    }
}
