<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function showForm()
    {
        return view('admin.notification');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $message = $request->input('message');

        // デバッグ用ログ
        \Log::info('Sending email with subject: ' . $subject);
        \Log::info('Message content: ' . $message);

        // Mailtrapを使用してテストメールを送信
        Mail::to('test@example.com')->send(new UserNotification($subject, $message));

        return redirect()->back()->with('success', 'お知らせメールが送信されました。');
    }
}