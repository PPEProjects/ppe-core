<?php

namespace ppeCore\dvtinh\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;

use ppeCore\dvtinh\Models\PasswordReset;
use ppeCore\dvtinh\Notifications\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    /**
     * Create token password reset.
     *
     * @param  ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function sendMail(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(60),
        ]);
        if ($passwordReset) {
            $user->notify(new ResetPassword($passwordReset->token));
        }

        return response()->json([
        'message' => 'We have e-mailed your password reset link!',
        ]);
    }

    public function reset(Request $request)
    {
        $passwordReset = \ppeCore\dvtinh\Models\PasswordReset::where('token', $request['token'])->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            throw new Exception(__('ppe.invalid_credentials'));
//            return response()->json([
//                'message' => 'This password reset token is invalid.',
//            ], 422);
        }
        $user = User::where('email', $passwordReset->email)->firstOrFail();
        $updatePasswordUser = $user->password = Hash::make($request['password']);
        $user->save();
        $passwordReset->delete();

        return response()->json([
            'message' => "success",
        ]);
    }
}