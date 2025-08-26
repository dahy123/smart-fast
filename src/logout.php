<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Répondre au front-end
echo json_encode(['success' => true]);

exit;