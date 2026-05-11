<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function sendMessage($target, $message)
    {
        try {

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target'  => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            Log::info('FONNTE SUCCESS', [
                'target' => $target,
                'message' => $message,
                'response' => $response->json()
            ]);

            return $response->json();

        } catch (\Exception $e) {

            Log::error('FONNTE ERROR', [
                'message' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'reason' => $e->getMessage()
            ];
        }
    }
}