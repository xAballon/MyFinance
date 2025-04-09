<?php
 $url = trim(str_replace('/Misc', '', $$_SERVER['REQUEST_URI']), '/');

 switch ($url) {
    case '':
        // Weiterleitung zu einer 'about'-Seite oder das Laden der entsprechenden Inhalte
        include('../index.php');
        break;
    
    case 'login':
        // Weiterleitung zu einer 'contact'-Seite oder das Laden der entsprechenden Inhalte
        include('../login/login.php');
        break;
    
    default:
        // Standard-Antwort, wenn keine übereinstimmende Route gefunden wurde
        include('404.php');
        break;
}

?>