<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(20);
        foreach ($posts as $post) {
            $post->likesCount = $post->likes->count();
            $post->commentsCount = $post->comments->count();
            $post->liked = $post->likes->contains('user_id', Auth::id());
        }
        return response()->json($posts, 200);
    }

    //show detail post
    public function show($postid)
    {
        $post = Post::with('user')->find($postid);
        if (!$post) {
            return response()->json(['error' => 'Post not found!'], 404);
        }
        $post->likesCount = $post->likes->count();
        $post->commentsCount = $post->comments->count();
        $post->liked = $post->likes->contains('user_id', Auth::id());
        return response()->json(['post' => $post], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = Auth::user(); //get info about the current user login
        if ($user != null) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/posts');
                $image->move($destinationPath, $name);
                $data['image_url'] = $name;
            }
            $data['user_id'] = $user->id; //current with user logged
            $post = Post::create($data);
            return response()->json(['post' => $post], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    //
    public function update(Request $request, $id)
    {
        $data = $request->all();    //get all the data from the request
        $user = Auth::user();   //get info about the current logged in user
        if ($user != null) {
            $post = Post::find($id); //find id in db
            if ($post->user_id == $user->id) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $name = time() . '.' . $image->getClientOriginalExtension();
                    $destination = public_path('/images');
                    $image->move($destination, $name);
                    $data['image_url'] = $name;
                }
                $post->update($data);
                $oldImage = $post->image_url;
                if ($oldImage != null) {
                    $path = public_path() . '/images' . $oldImage;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                return response()->json(['post' => $post], 200);
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }


    //
    public function destroy($id)
    {
        $user = Auth::user(); //get info about the current logged user
        if ($user != null) {
            $post = Post::find($id);
            if ($post->user_id == $user->id) { //if the post belongs to current user logged
                $post->delete();
                return response()->json(['message' => "Post delete Successfully"], 200);
            }
            return;
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
