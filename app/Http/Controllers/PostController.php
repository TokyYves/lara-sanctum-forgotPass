<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user')->inRandomOrder()->paginate(5);

        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $post = Post::create($request->post());
        // $post = Post::create($request->post());

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post = Post::with('user')->where('id', $post->id)->get();

        if (is_null($post)) {
            return response()->json([
                'message' => 'Something goes wrong while deleting a product!!'
            ]);
        }
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (! Gate::allows('update-post', $post)) {
            return response()->json([
                'message' => 'you are not authorized to edit this post'
            ],403);
        }
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $post->fill($request->post())->update();

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (! Gate::allows('update-post', $post)) {
            return response()->json([
                'message' => 'you are not authorized to delete this post'
            ],403);
        }
        $post->delete();

        return response()->json([
            'message' => 'Product Deleted Successfully!!'
        ]);
    }
}
