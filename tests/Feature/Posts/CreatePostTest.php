<?php

namespace Tests\Feature\Posts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    /** @test */
    public function user_can_create_post_if_data_is_valid()
    {
        $dataCreate = [
            'name' => $this->faker->name,
            'body' => $this->faker->text,
        ];

        // Gửi yêu cầu POST
        $response = $this->json('POST', route('posts.store'), $dataCreate);

        // Kiểm tra mã trạng thái phản hồi
        $response->assertStatus(Response::HTTP_CREATED);

        // Kiểm tra JSON phản hồi
        $response->assertJson(fn(AssertableJson $json) =>
        $json->where('message', 'Post created successfully')
            ->has('data', fn(AssertableJson $json) =>
            $json->where('id', fn($id) => is_int($id)) // Kiểm tra ID tồn tại và là số
                ->where('name', $dataCreate['name']) // Kiểm tra name
                ->etc()
            )
        );

        // Kiểm tra dữ liệu trong cơ sở dữ liệu
        $this->assertDatabaseHas('posts', [
            'name' => $dataCreate['name'],
            'body' => $dataCreate['body'],
        ]);
    }
    /** @test */
    public function user_can_not_create_post_if_name_is_null()
    {
        $dataCreate = [
            'name' => '', // Trường name rỗng
            'body' => $this->faker->text, // Trường body hợp lệ
        ];

        // Gửi yêu cầu POST
        $response = $this->postJson(route('posts.store'), $dataCreate);

        // Kiểm tra mã trạng thái phản hồi
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Kiểm tra JSON phản hồi
        $response->assertJson(fn(AssertableJson $json) =>
        $json->has('errors', fn(AssertableJson $json) =>
            $json->has('name')
                ->etc()
            )->etc()
        );
    }
    /** @test */
    public function user_can_not_create_post_if_body_is_null()
    {
        $dataCreate = [
            'name' => $this->faker->name, // Trường name hợp lệ
            'body' => '', // Trường body rỗng
        ];

        // Gửi yêu cầu POST
        $response = $this->postJson(route('posts.store'), $dataCreate);

        // Kiểm tra mã trạng thái phản hồi
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Kiểm tra JSON phản hồi
        $response->assertJson(fn(AssertableJson $json) =>
        $json->has('errors', fn(AssertableJson $json) =>
            $json->has('body')
                ->etc()
            )->etc()
        );
    }
    /** @test */
    public function user_can_not_create_post_if_data_is_invalid()
    {
        $dataCreate = [
            'name' => '',
            'body' => '',
        ];

        // Gửi yêu cầu POST
        $response = $this->postJson(route('posts.store'), $dataCreate);

        // Kiểm tra mã trạng thái phản hồi
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Kiểm tra JSON phản hồi
        $response->assertJson(fn(AssertableJson $json) =>
        $json->has('errors', fn(AssertableJson $json) =>
            $json->has('name')
                ->has('body')
                ->etc()
            )->etc()
        );
    }

}