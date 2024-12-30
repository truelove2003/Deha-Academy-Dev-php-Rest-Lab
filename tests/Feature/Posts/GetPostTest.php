<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GetPostTest extends TestCase
{
    /** @test */
    public function user_can_get_post_if_post_exits()
    {
        $post = Post::factory()->create(); // Tạo một bài viết mẫu
        $response = $this->getJson(route('posts.show', $post->id)); // Gửi yêu cầu GET để lấy bài viết

        $response->assertStatus(Response::HTTP_OK); // Đảm bảo phản hồi có mã HTTP 200

        $response->assertJson(fn(AssertableJson $json) =>
        $json->where('message', 'Post retrieved successfully') // Kiểm tra thông báo phản hồi
            ->has('data', fn(AssertableJson $json) => // Kiểm tra dữ liệu trong phần 'data'
            $json->where('id', $post->id) // Đúng 'id'
                ->where('name', $post->name) // Đúng 'name'
                ->etc() // Bỏ qua các trường khác nếu có
            )
        );
    }
    /** @test */
    public function user_can_not_get_post_if_post_not_exits()
    {
        $postID=-1;
        $response = $this->getJson(route('posts.show', $postID)); // Gửi yêu cầu GET để lấy bài viết
        $response->assertStatus(Response::HTTP_NOT_FOUND); // Đảm bảo phản hồi có mã HTTP 404
    }
}