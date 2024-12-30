<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    /** @test */
    public function user_can_update_post_if_post_exits_and_data_is_valid()
    {
        $post=Post::factory()->create(); // Tạo một bài viết mẫu
        $dataUpdate = [
            'name' => $this->faker->name,
            'body' => $this->faker->text,
        ];
        $response=$this->putJson(route('posts.update', $post->id), $dataUpdate); // Gửi yêu cầu PUT để cập nhật bài viết
        $response->assertStatus(Response::HTTP_OK); // Đảm bảo phản hồi có mã HTTP 200
        $response->assertJson(fn(AssertableJson $json) =>
        $json->has('data', fn(AssertableJson $json) => // Kiểm tra dữ liệu trong phần 'data'
            $json->where('name', $dataUpdate['name']) // Đúng 'name'
                ->etc()
            )->etc()
        );
        $this->assertDatabaseHas('posts', [
            'name' => $dataUpdate['name'],
            'body' => $dataUpdate['body'],
        ]);

    }
    /** @test */
    public function user_can_not_update_post_if_post_exits_and_name_is_null()
    {
        $post = Post::factory()->create(); // Tạo một bài viết mẫu
        $dataUpdate = [
            'name' => '',
            'body' => $this->faker->text,
        ];
        $response = $this->putJson(route('posts.update', $post->id), $dataUpdate); // Gửi yêu cầu PUT để cập nhật bài viết
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); // Đảm bảo phản hồi có mã HTTP 422
        $response->assertJson(fn(AssertableJson $json) =>
            $json->has('errors', fn(AssertableJson $json) => // Kiểm tra dữ liệu trong phần 'errors'
                $json->has('name') // Đúng 'name'
            )
        );
    }
    /** @test */
    public function user_can_not_update_post_if_post_exits_and_body_is_null()
    {
        $post = Post::factory()->create(); // Tạo một bài viết mẫu
        $dataUpdate = [
            'name' => $this->faker->name,
            'body' => '',
        ];
        $response = $this->putJson(route('posts.update', $post->id), $dataUpdate); // Gửi yêu cầu PUT để cập nhật bài viết
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); // Đảm bảo phản hồi có mã HTTP 422
        $response->assertJson(fn(AssertableJson $json) =>
            $json->has('errors', fn(AssertableJson $json) => // Kiểm tra dữ liệu trong phần 'errors'
                $json->has('body') // Đúng 'body'
            )
        );

    }
    /** @test */
    public function user_can_not_update_post_if_post_exits_and_data_is_not_valid()
    {
        $post = Post::factory()->create(); // Tạo một bài viết mẫu
        $dataUpdate = [
            'name' => '',
            'body' => '',
        ];
        $response = $this->putJson(route('posts.update', $post->id), $dataUpdate); // Gửi yêu cầu PUT để cập nhật bài viết
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); // Đảm bảo phản hồi có mã HTTP 422
        $response->assertJson(fn(AssertableJson $json) =>
            $json->has('errors', fn(AssertableJson $json) => // Kiểm tra dữ liệu trong phần 'errors'
                $json->has('name') // Kiểm tra sự tồn tại của 'name'
                    ->has('body') // Kiểm tra sự tồn tại của 'body'
            )
        );
    }
    /** @test */
    public function user_can_not_update_post_if_post_not_exits_and_data_is_valid()
    {
        $postID = -1;
        $dataUpdate = [
            'name' => $this->faker->name,
            'body' => $this->faker->text,
        ];
        $response = $this->putJson(route('posts.update', $postID), $dataUpdate); // Gửi yêu cầu PUT để cập nhật bài viết
        $response->assertStatus(Response::HTTP_NOT_FOUND); // Đảm bảo phản hồi có mã HTTP 404
    }
}