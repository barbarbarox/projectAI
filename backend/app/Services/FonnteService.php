<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN', '');
    }

    /**
     * Kirim pesan WhatsApp via Fonnte API.
     *
     * @param string $phone Nomor telepon tujuan (format: 628xxxxxxxxx)
     * @param string $message Isi pesan
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            Log::error('Fonnte token belum dikonfigurasi.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? false) === true) {
                    Log::info("Fonnte OTP terkirim ke: {$phone}");
                    return true;
                }
                Log::warning("Fonnte response error: " . json_encode($data));
                return false;
            }

            Log::error("Fonnte HTTP error: {$response->status()} - {$response->body()}");
            return false;
        } catch (\Exception $e) {
            Log::error("Fonnte send failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim kode OTP via WhatsApp.
     */
    public function sendOtp(string $phone, string $otpCode): bool
    {
        $message = "🔐 *Kode OTP RedSim*\n\n"
            . "Kode verifikasi Anda: *{$otpCode}*\n\n"
            . "Kode ini berlaku selama 5 menit.\n"
            . "Jangan bagikan kode ini kepada siapapun.\n\n"
            . "— Tim RedSim";

        return $this->sendMessage($phone, $message);
    }
}
