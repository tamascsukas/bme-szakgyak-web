<?php

namespace App\Http\Controllers\Auth;

use App\VerificationToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(VerificationToken $token)
    {
        $token->user()->update([
            'verified' => true
        ]);

        $token->delete();

        return "Fiók aktiválva!";
    }
}
