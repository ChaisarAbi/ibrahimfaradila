<?php namespace App\Libraries;

use App\Models\TelegramRecipientModel;

class TelegramBot
{
    private $botToken;
    private $chatId;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN', '');
        $this->chatId = env('TELEGRAM_CHAT_ID', '');
    }

    public function sendMessage($message, $targetChatId = null)
    {
        if (empty($this->botToken)) {
            return false;
        }

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        // If specific chat ID is given, send only to that one
        if ($targetChatId !== null) {
            return $this->sendToChat($url, $targetChatId, $message);
        }

        // Otherwise, send to all active recipients from database
        $recipientModel = new TelegramRecipientModel();
        $recipients = $recipientModel->where('is_active', 1)->findAll();

        if (empty($recipients)) {
            // Fallback to default env chat ID
            if (!empty($this->chatId)) {
                return $this->sendToChat($url, $this->chatId, $message);
            }
            return false;
        }

        $results = [];
        foreach ($recipients as $recipient) {
            $result = $this->sendToChat($url, $recipient['chat_id'], $message);
            $results[] = $result;
        }
        return $results;
    }

    private function sendToChat($url, $chatId, $message)
    {
        $postData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}