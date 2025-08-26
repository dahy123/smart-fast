<?php 
require_once __DIR__ . '/app/database.php';

// Création de la base de donnée et des tables
Database::create_tables();

// Redirection vers la page de connexion
header('location:./src/login.php')
?>