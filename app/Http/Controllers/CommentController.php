<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Validator;

class CommentController extends Controller
{
    public function show($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found!'], 404);
        }
        $commet = $post->comments()->with('user')->latest(); //get all the comments for the post
        return response()->json(['comments' => $commet], 200);
    }
    //store comment
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'post_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $data = $request->all(); //get all data from the request
        $user = auth()->user(); //get the currently authenticated user login
        $post = Post::find($data['post_id']); //get the post with the given id;
        if (!$post) {
            return response()->json(['error' => 'Post not found!'], 404);
        }
        $commet = $post->comments()->create([
            'user_id' => $user->id,
            'comment' => $data['comment'],
            'post_id' => $post->id
        ]);
        return response()->json(['data' => $commet], 200);
    }

    //update comment 
    public function update(Request $request, $id)
    {
        $data = $request->all(); //get all data from request
        $user = auth()->user(); //get currently user authenticated login
        $comment = Comment::find($id); //check in db have this id that user want update or not
        if (!$comment) {
            return response()->json(['error' => 'Comment not found!'], 404);
        }
        if ($comment->user_id != $user->id) { //check user_id comment = current user login with token or not
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $comment->update($data);
        return response()->json(['data' => $comment], 200);
    }

    //function delete comment
    public function destroy($id)
    {
        $user = auth()->user(); //get currently user login authenticated
        $comment = Comment::find($id); //check in db with id user want to delete have in db or not
        if (!$comment) {
            return response()->json(['error' => 'Comment not found!'], 404);
        }
        //check if the user is the owner of comment 
        if ($comment->user_id != $user->id) { //check usercommnet = user current login with token or not
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $comment->delete();
        return response()->json(['data' => $comment], 200);
    }
}
