<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function send_verification_email()
    {
        return "Mohon Verifikasi Email";
    }

    public function verification_email(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return "Email Berhasil diVerifikasi";
    }

    public function resend_verification_email(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return "Email Verifikasi Berhasil dikirim";
    }
}
