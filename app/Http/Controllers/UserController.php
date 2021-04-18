<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
