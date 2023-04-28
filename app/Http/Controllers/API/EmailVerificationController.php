<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


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

    public function resend_verification_email(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return "Email Verifikasi Berhasil dikirim";
    }
}
