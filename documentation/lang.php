<?php

session_start();

$lang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING);

if ($lang == 'br') {
    setcookie('lang', 'br', time() + (86400 * 30), "/");
} else {
    setcookie('lang', 'en', time() + (86400 * 30), "/");
}

header('Location: ./');
