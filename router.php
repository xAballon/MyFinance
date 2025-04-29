<?php
// Ermittelt den Projektordnernamen (MyFinance)
$projectFolder = basename(__DIR__);
# echo $projectFolder; // Gibt den Ordnernamen aus, z.B. "MyFinance"

// Dann wird die URL basierend auf diesem Namen dynamisch erstellt
$baseUrl = "/" . $projectFolder;  // /MyFinance

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case '':
    case 'home':
        require 'index.php';
        break;
    case 'kontakt':
        require 'kontakt.php';
        break;
    case 'impressum':
        require 'impressum.php';
        break;
    case 'about':
        require 'about.php';
        break;
    case 'login':
        require 'login/login.php';
        break;
    default:
        http_response_code(404);
        require '404.php';
        break;
}
