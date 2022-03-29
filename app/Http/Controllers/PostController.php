<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get data from table posts
        $posts = Post::latest()->paginate(5);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List All Post',
            'data'    => $posts
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'image' => 'image|file|max:2048',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // save images
        if ($request->file('image')) {
            $imageName = $request->file('image')->store('post-images');
        }

        //save to database
        $post = Post::create([
            'title'     => $request->title,
            'user_id'   => $request->user_id,
            'category_id' => $request->category_id,
            'content'   => $request->content,
            'image' => $imageName,
        ]);

        //success save to database
        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post Created',
                'data'    => $post,
            ], 201);
        }

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Post Failed to Save',
        ], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find post by ID
        $post = Post::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Post',
            'data'    => $post
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'image' => 'image|file|max:2048',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find post by ID
        $post = Post::findOrFail($post->id);


        if ($post) {
            // update images
            if ($request->file('image')) {
                if ($post->image) {
                    Storage::delete($post->image);
                }
                $imageName = $request->file('image')->store('post-images');
            }
            //update post
            $post->update([
                'title'     => $request->title,
                'user_id'   => $request->user_id,
                'category_id' => $request->category_id,
                'content'   => $request->content,
                'image' => $imageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post Updated',
                'data'    => $post
            ], 200);
        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Post Not Found',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //find post by ID
        $post = Post::findOrfail($post->id);

        if ($post) {
            //delete image
            if ($post->image) {
                Storage::delete($post->image);
            }

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post Deleted',
            ], 200);
        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Post Not Found',
        ], 404);
    }
}
