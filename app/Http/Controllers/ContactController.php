<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name_user' => 'required',
            'email_user' => 'required|email',
            'descr_user' => 'required',
        ]);

        $data = [
            'name' => $request->name_user,
            'email' => $request->email_user,
            'message' => $request->descr_user,
        ];

         Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactFormMail($data));

        return redirect()->back()->with('success', 'Ваше сообщение успешно отправлено. Мы свяжемся с вами в ближайшее время!');
    }
}