<?php
class Database
{
    // Configuration
    private static $host = "localhost";
    private static $db = "smart_fast";
    private static $user = "root";
    private static $password = "1234";

    // Constantes pour les rôles et statuts
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_MODERATOR = 'moderator';

    const STATUT_ATTENTE = 'en_attente';
    const STATUT_VALIDE = 'valide';
    const STATUT_REJETE = 'rejete';

    // Connexion PDO
    public static function pdo($useDatabase = true)
    {
        try {
            $dsn = $useDatabase
                ? "mysql:host=" . self::$host . ";dbname=" . self::$db
                : "mysql:host=" . self::$host;
            $pdo = new PDO($dsn, self::$user, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            error_log("PDO Connection failed: " . $e->getMessage());
            return null;
        }
    }

    // Création de la base de données
    public static function create_database()
    {
        $pdo = self::pdo(false);
        if ($pdo) {
            try {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS " . self::$db);
            } catch (PDOException $e) {
                error_log("Erreur création DB : " . $e->getMessage());
            }
        }
    }

    // Création des tables
    public static function create_tables()
    {
        self::create_database();
        $pdo = self::pdo();
        if (!$pdo) return;

        $tables = [
            // utilisateurs
            "CREATE TABLE IF NOT EXISTS utilisateurs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(100),
                prenom VARCHAR(100),
                telephone VARCHAR(20) UNIQUE,
                mot_de_passe VARCHAR(255),
                role ENUM('" . self::ROLE_USER . "', '" . self::ROLE_ADMIN . "', '" . self::ROLE_MODERATOR . "') DEFAULT '" . self::ROLE_USER . "',
                parrain_id INT,
                date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (parrain_id) REFERENCES utilisateurs(id)
            ) ENGINE=InnoDB",

            // balances
            "CREATE TABLE IF NOT EXISTS balances (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT UNIQUE,
                total DECIMAL(15,2) DEFAULT 0,
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
            ) ENGINE=InnoDB",

            // depots
            "CREATE TABLE IF NOT EXISTS depots (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT,
                montant DECIMAL(15,2),
                reference VARCHAR(100),
                preuve_image VARCHAR(255),
                statut ENUM('" . self::STATUT_ATTENTE . "', '" . self::STATUT_VALIDE . "', '" . self::STATUT_REJETE . "') DEFAULT '" . self::STATUT_ATTENTE . "',
                date_depot DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
            ) ENGINE=InnoDB",

            // niveaux
            "CREATE TABLE IF NOT EXISTS niveaux (
                id INT AUTO_INCREMENT PRIMARY KEY,
                niveau INT UNIQUE,
                investissement DECIMAL(15,2),
                apprendre DECIMAL(15,2),
                mise_a_niveau DECIMAL(15,2),
                profil_restant DECIMAL(15,2),
                parrainage INT
            ) ENGINE=InnoDB",

            // investissements
            "CREATE TABLE IF NOT EXISTS investissements (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT,
                niveau_id INT,
                montant DECIMAL(15,2),
                date_investissement DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (niveau_id) REFERENCES niveaux(id),
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
            ) ENGINE=InnoDB",

            // retraits
            "CREATE TABLE IF NOT EXISTS retraits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT,
                montant DECIMAL(15,2),
                methode VARCHAR(100),
                preuve_image VARCHAR(255),
                statut ENUM('" . self::STATUT_ATTENTE . "', '" . self::STATUT_VALIDE . "', '" . self::STATUT_REJETE . "') DEFAULT '" . self::STATUT_ATTENTE . "',
                date_retrait DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
            ) ENGINE=InnoDB"
        ];

        try {
            foreach ($tables as $sql) {
                $pdo->exec($sql);
            }
            self::insert_default_admin($pdo);
            self::insert_default_niveaux($pdo);
        } catch (PDOException $e) {
            error_log("Erreur création tables : " . $e->getMessage());
        }
    }

    // Insertion de l'admin par défaut
    private static function insert_default_admin($pdo)
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE telephone = '0329375154'");
        if ($stmt->fetchColumn() == 0) {
            $hash = password_hash('1234', PASSWORD_DEFAULT);
            $stmtInsert = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, telephone, mot_de_passe, role)
                                         VALUES ('Dahy', 'Oldon', '0329375154', :hash, :role)");
            $stmtInsert->execute(['hash' => $hash, 'role' => self::ROLE_ADMIN]);

            $adminId = $pdo->lastInsertId();
            $pdo->prepare("INSERT INTO balances (utilisateur_id, total) VALUES (?, 0)")->execute([$adminId]);
        }
    }

    // Insertion des niveaux par défaut
    private static function insert_default_niveaux($pdo)
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM niveaux");
        if ($stmt->fetchColumn() == 0) {
            $niveauxData = [
                [1, 5000, 10000, 7500, 2500, 2],
                [2, 7500, 30000, 15000, 15000, 4],
                [3, 15000, 120000, 60000, 60000, 8],
            ];
            $stmtInsert = $pdo->prepare("INSERT INTO niveaux (niveau, investissement, apprendre, mise_a_niveau, profil_restant, parrainage)
                                         VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($niveauxData as $row) {
                $stmtInsert->execute($row);
            }
        }
    }
}
?>
