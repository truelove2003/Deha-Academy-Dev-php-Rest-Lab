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
     */
    public function index()
    {
        $posts=$this->post->paginate(10);
        //$postCollection=new PostCollection($posts);
        //return $this->sentSuccessResponse($postCollection,'Posts retrieved successfully',Response::HTTP_OK);
        $postResource = PostResource::collection($posts)->response()->getData(true);
        return \response()->json([
            'data'=>$postResource,
            'message'=>'Posts retrieved successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $dataCreate = $request->all();
        $post = $this->post->create($dataCreate);
        $postResource = new PostResource($post);
        return $this->sentSuccessResponse($postResource,'Post created successfully',Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = $this->post->findOrFail($id);
        $postResource = new PostResource($post);
        return $this->sentSuccessResponse($postResource,'Post retrieved successfully',Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = $this->post->findOrFail($id);
    $dataUpdate = $request->all();
    $post->update($dataUpdate);
    $postResource = new PostResource($post);

    return $this->sentSuccessResponse($postResource, 'Post updated successfully', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = $this->post->findOrFail($id);
        $post->delete();
        $postResource = new PostResource($post);
        return $this->sentSuccessResponse($postResource,'Post deleted successfully',Response::HTTP_OK);
    }
}