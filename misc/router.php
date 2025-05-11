<?php

$url = $_GET['url'] ?? '';
$parts = explode('/', trim($url, '/'));

switch ($parts[0]) {
    case '':
        require 'index.php'; // Startseite
        break;
    case 'login':
        require 'login/login.php';
        break;
    case 'dashboard':
        require 'transaction/transactions.php?type=eingang';
        break;
    case 'konto':
        if (isset($parts[1])) {
            $_GET['id'] = $parts[1]; // Optional, falls du mit id arbeitest
            require 'konto/detail.php';
        } else {
            require 'konto/index.php';
        }
        break;
    default:
        http_response_code(404);
        echo "404 - Seite nicht gefunden.";
}

?>