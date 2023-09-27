<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Validator;

class FollowController extends Controller
{
    public function follow(Request $requset)
    {
        $data = $requset->all();
        $validator = Validator::make($data, [
            'user_id' => 'required', //user_id is the id of the user who is following
            'following_user_id' => 'required', //following_user_id is the id of the user who begin followed;
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'Validator Error']);
        }
        $follow = Follow::create($data);
        return response()->json(['follow' => $follow, 'message' => 'Create Successfully'], 200);
    }

    //
    public function unFollow(Request $request)
    {
        $follow = Follow::where('user_id', $request->user_id)->where('following_user_id', $request->following_user_id)->first();
        if ($follow) {
            $follow->delete();
            return response(['message' => 'Deleted']);
        }
        return response(['message' => 'Not found']);
    }
}
