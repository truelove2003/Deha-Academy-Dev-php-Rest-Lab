<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    /** @test */
    public function user_can_delete_post_if_post_exits()
    {
        $post=Post::factory()->create(); // Tạo một bài viết mẫu
        $postCountBeforeDelete=Post::count(); // Đếm số bài viết trước khi xóa
        $response=$this->deleteJson(route('posts.destroy', $post->id)); // Gửi yêu cầu DELETE để xóa bài viết
        $response->assertStatus(Response::HTTP_OK); // Đảm bảo phản hồi có mã HTTP 200
        $response->assertJson(fn(AssertableJson $json) =>
            $json->has('data',fn(AssertableJson $json)=>
                $json->where('name',$post->name) // Đúng 'name'
                    ->etc()
            )->etc()
        );
        $postCountAfterDelete=Post::count(); // Đếm số bài viết sau khi xóa
        $this->assertEquals($postCountBeforeDelete-1,$postCountAfterDelete);
    }
    /** @test */
    public function user_can_not_delete_post_if_post_not_exits()
    {
        $postID=-1;
        $response = $this->deleteJson(route('posts.destroy', $postID)); // Gửi yêu cầu DELETE để xóa bài viết
        $response->assertStatus(Response::HTTP_NOT_FOUND); // Đảm bảo phản hồi có mã HTTP 404
    }
}