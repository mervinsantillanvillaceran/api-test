<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function confirm(Request $request)
    {
        $pin = $request->get('pin');
        $user = auth()->user();

        if ($pin != $user->pin) {
            return response()->json('Invalid pin.', 422);
        }

        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();

        return response()->json('Successfully validated.');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4|max:20',
            'user_name' => "required|min:4|max:20|unique:App\Models\User,user_name,{$user->id}",
            'avatar' => 'image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $avatar = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('images'), $avatar);

        $user->name = $validator->validated()['name'];
        $user->user_name = $validator->validated()['user_name'];
        $user->avatar = $avatar;
        $user->save();

        return response()->json('Successfully updated.');
    }
}
