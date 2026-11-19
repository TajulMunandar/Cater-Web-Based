<?php

namespace App\Services;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PushNotificationService
{
    public function __construct(private Messaging $messaging)
    {
    }

    /**
     * Kirim notifikasi ke semua HP milik satu user. Tidak pernah melempar
     * exception supaya proses utama (mis. tolak catatan) tidak ikut gagal.
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = FcmToken::where('user_id', $userId)->pluck('token')->all();
        if (empty($tokens)) {
            return;
        }

        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData(array_map('strval', $data))
                ->withAndroidConfig([
                    'priority' => 'high',
                    'notification' => ['channel_id' => 'catat_meter_ditolak'],
                ]);

            $report = $this->messaging->sendMulticast($message, $tokens);

            // Bersihkan token yang sudah tidak valid (app di-uninstall, dsb.)
            $invalid = array_merge($report->unknownTokens(), $report->invalidTokens());
            if ($invalid) {
                FcmToken::whereIn('token', $invalid)->delete();
            }
        } catch (\Throwable $e) {
            Log::warning('FCM gagal terkirim: ' . $e->getMessage());
        }
    }
}
