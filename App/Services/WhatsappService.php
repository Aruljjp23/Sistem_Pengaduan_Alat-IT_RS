<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function sendWithToken($token, $target, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->asForm()->post($this->apiUrl, [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->json();
    }

    public function getTokenByPengadu($nomor)
    {
        $tokens = [
            config('services.fonnte.pengadu1'),
            config('services.fonnte.pengadu2'),
            config('services.fonnte.pengadu3'),
        ];

        $index = crc32($nomor) % count($tokens);

        return $tokens[$index];
    }

    public function send($nomorPengadu, $target, $message)
    {
        $token = $this->getTokenByPengadu($nomorPengadu);

        try {

            $response = Http::withHeaders([
                'Authorization' => $token
            ])->asForm()->post($this->apiUrl, [
                'target' => $target,
                'message' => $message
            ]);

            $result = $response->json();

            Log::info("WA dikirim", [
                'token' => $token,
                'target' => $target,
                'result' => $result
            ]);

            return $result;

        } catch (\Exception $e) {

            Log::error("WA gagal", [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    public function sendPengadu($nomor, $message)
    {
        return $this->send($nomor, $nomor, $message);
    }
}