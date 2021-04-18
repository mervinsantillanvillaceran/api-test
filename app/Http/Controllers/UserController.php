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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->name = $validator->validated()['name'];
        $user->user_name = $validator->validated()['user_name'];
        $user->save();

        return response()->json('Successfully updated.');
    }
}
