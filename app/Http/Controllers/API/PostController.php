<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{

    protected $post;

    /**
     * @param $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = $this->post->paginate(5);

        $postCollection = PostResource::collection($posts)->response()->getData(true);

        return $this->sentSuccessResponse($postCollection,'success', Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $dataCreate =  $request->all();
        $post = $this->post->create($dataCreate);

        $postResource = new PostResource($post);

        return $this->sentSuccessResponse($postResource,'success', Response::HTTP_OK);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post =  $this->post->findOrFail($id);
        $postResource = new PostResource($post);

        return $this->sentSuccessResponse($postResource,'success', Response::HTTP_OK);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post =  $this->post->findOrFail($id);

        $dataUpdate = $request->all();

        $post->update($dataUpdate);

        $postResource = new PostResource($post);

        return $this->sentSuccessResponse($postResource,'success', Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->post->findOrFail($id);
        $post->delete();
        $postResource =  new PostResource($post);

        return $this->sentSuccessResponse($postResource,'success', Response::HTTP_OK);

    }
}
