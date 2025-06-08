<?php

namespace App\Services;

use App\Models\OrderConfirmation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class OrderConfirmationService
{
    public function createConfirmation(array $orderData, string $email): OrderConfirmation
    {
        $confirmation = OrderConfirmation::create([
            'email' => $email,
            'confirmation_code' => Str::random(6),
            'order_data' => $orderData,
            'expires_at' => now()->addHours(24),
        ]);

        Mail::to($email)->send(new OrderConfirmationMail($confirmation));

        return $confirmation;
    }

    public function confirmOrder(string $code, string $email): bool
    {
        $confirmation = OrderConfirmation::where('confirmation_code', $code)
            ->where('email', $email)
            ->where('is_confirmed', false)
            ->first();

        if (!$confirmation || $confirmation->isExpired()) {
            return false;
        }

        $confirmation->update(['is_confirmed' => true]);
        return true;
    }
} 