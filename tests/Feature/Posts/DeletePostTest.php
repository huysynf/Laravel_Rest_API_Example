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

    public function user_can_delete_post_if_post_exists()
    {
        $post = Post::factory()->create();
        $postCountBeforeDelete = Post::count();
        $response =  $this->json('DELETE', route('posts.destroy', $post->id));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn ( AssertableJson $json) =>
            $json->has('data', fn(AssertableJson $json) =>
                $json->where('name', $post->name)->etc()
            )->etc()
        );
        $postCountAfterDelete = Post::count();

        $this->assertEquals($postCountBeforeDelete-1, $postCountAfterDelete);

    }

    /** @test  */

    public function user_can_not_delete_post_if_post_not_exists()
    {
        $postId = -1;
        $response =  $this->json('DELETE', route('posts.destroy', $postId));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

    }

}
