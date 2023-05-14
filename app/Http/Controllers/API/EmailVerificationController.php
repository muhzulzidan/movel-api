<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Log;


class EmailVerificationController extends Controller
{
    public function send_verification_email(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email Telah diVerifikasi!'
            ];
        }

        $request->user()->sendEmailVerificationNotification();

        return ['status' => 'Link Verifikasi Email Telah dikirim!'];
    }

    public function verify(EmailVerificationRequest $request)
    {
        Log::info('verify function called');
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email Telah diVerifikasi!'
            ];
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        return [
            'message'=>'Email berhasil diVerifikasi!'
        ];
    }

    // public function verifyEmail(Request $request)
    // {
    //     $request->fulfill();

    //     return redirect('/home')->with('status', 'Your email has been verified.');
    // }

public function verifyEmail(Request $request)
{
    $user = $request->user();
    $user->markEmailAsVerified();

    return view('email-verified');
}


    public function resend_verification_email(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return "Email Verifikasi Berhasil dikirim";
    }




}
