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

    public function user_can_get_post_if_post_exists()
    {
        $post = Post::factory()->create();

        $response =  $this->getJson(route('posts.show', $post->id));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('message')
            ->has('data', fn(AssertableJson $json) =>
                $json->where('name', $post->name)
                ->etc()
            )
        );
    }


    /** @test */

    public function user_can_not_get_post_if_post_not_exists()
    {
        $postId = -1;

        $response = $this->getJson(route('posts.show', $postId));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

}
