<?php
session_start();

// Détruire toutes les données de session
$_SESSION = [];
session_destroy();

// Répondre en JSON
header("location:login.php");
// header('Content-Type: application/json');
// echo json_encode(['success' => true]);
exit;
