<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../actions/config.php';

// language pack
$langCode = $_SESSION['lang'] ?? 'da';
$lang = require __DIR__ . "/../lang/{$langCode}.php";

function t(string $key): string {
    global $lang;
    return $lang[$key] ?? $key;
}

// translate short date to danish
function formatDateDa(string $date): string {
    $en = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $da = ['Jan','Feb','Mar','Apr','Maj','Jun','Jul','Aug','Sep','Okt','Nov','Dec'];

    return str_replace($en, $da, date('d-M-Y', strtotime($date)));
}