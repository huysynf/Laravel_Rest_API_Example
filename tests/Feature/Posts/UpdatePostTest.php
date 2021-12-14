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

    public function user_can_update_post_if_post_exists_and_data_is_vald()
    {
        $post =  Post::factory()->create();
        $dataUpdate = [
          'name' =>  $this->faker->name,
          'body' => $this->faker->text
        ];

        $response =  $this->json('PUT', route('posts.update', $post->id), $dataUpdate);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn(AssertableJson $json) =>
                $json->where('name', $dataUpdate['name'])
                ->etc()
            )->etc()
        );

        $this->assertDatabaseHas('posts', [
           'name' => $dataUpdate['name'],
           'body'=> $dataUpdate['body']
        ]);
    }

    /** @test */

    public function user_can_not_update_post_if_post_exists_and_name_is_null()
    {
        $post =  Post::factory()->create();
        $dataUpdate = [
            'name' => '',
            'body' => $this->faker->text
        ];

        $response =  $this->json('PUT', route('posts.update', $post->id), $dataUpdate);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(fn(AssertableJson $json) => $json->has('errors', fn(AssertableJson $json) => $json->has('name')
        ));
    }


    /** @test */

    public function user_can_not_update_post_if_post_exists_and_body_is_null()
    {
        $post =  Post::factory()->create();
        $dataUpdate = [
            'name' => $this->faker->name,
            'body' => ''
        ];

        $response =  $this->json('PUT', route('posts.update', $post->id), $dataUpdate);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(fn(AssertableJson $json) => $json->has('errors', fn(AssertableJson $json) => $json->has('body')
        ));
    }

    /** @test */

    public function user_can_not_update_post_if_post_exists_and_data_is_not_valid()
    {
        $post =  Post::factory()->create();
        $dataUpdate = [
            'name' => '',
            'body' => ''
        ];

        $response =  $this->json('PUT', route('posts.update', $post->id), $dataUpdate);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(fn(AssertableJson $json) => $json->has('errors', fn(AssertableJson $json) => $json->has('body')->has('name')
        ));
    }

    /** @test */

    public function user_can_not_update_post_if_post_nor_exists_and_data_ia_valid()
    {
        $postId =  -1;
        $dataUpdate = [
            'name' => $this->faker->name,
            'body' => $this->faker->text
        ];

        $response =  $this->json('PUT', route('posts.update', $postId), $dataUpdate);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
