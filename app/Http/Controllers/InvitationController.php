<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Jobs\SendEmailInvitationJob;

class InvitationController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:App\Models\User'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = $this->generateToken();
        $link = route('register') . '?invitation_token=' . $token;

        $user = new User();
        $user->email = $validator->validated()['email'];
        $user->invitation_token = $token;
        $user->save();

        dispatch(new SendEmailInvitationJob([
            'email' => $validator->validated()['email'],
            'link' => $link
        ]));

        return response()->json('Email sent successfully.');
    }

    private function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
