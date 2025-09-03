# Interface d'Administration SMART-FAST

## Vue d'ensemble

L'interface d'administration de SMART-FAST permet aux administrateurs de gérer tous les aspects de la plateforme :
- **Dashboard** : Statistiques et vue d'ensemble
- **Gestion des Dépôts** : Validation et rejet des demandes de dépôt
- **Gestion des Retraits** : Validation et rejet des demandes de retrait
- **Historique des Investissements** : Suivi des investissements des utilisateurs
- **Gestion des Utilisateurs** : Consultation des informations utilisateurs

## Accès

### Connexion Administrateur
- **Téléphone** : 0329375154
- **Mot de passe** : 1234
- **Rôle** : Administrateur

### URL d'accès
- Interface principale : `app.php`
- Interface d'administration : `admin.php`

## Fonctionnalités

### 1. Dashboard
- **Statistiques en temps réel** :
  - Total des dépôts validés
  - Total des retraits validés
  - Total des investissements
  - Nombre total d'utilisateurs
- **Actions en attente** :
  - Nombre de dépôts en attente de validation
  - Nombre de retraits en attente de validation
- **Graphique des dépôts** : Évolution des dépôts sur 7 jours

### 2. Gestion des Dépôts
- **Filtres disponibles** :
  - Recherche par nom, prénom, téléphone ou référence
  - Filtrage par statut (en attente, validé, rejeté)
  - Filtrage par date (début et fin)
- **Actions** :
  - ✅ **Valider** : Approuve le dépôt et crédite le solde utilisateur
  - ❌ **Rejeter** : Refuse le dépôt
- **Export** : Téléchargement des données en CSV

### 3. Gestion des Retraits
- **Filtres disponibles** :
  - Recherche par nom, prénom ou téléphone
  - Filtrage par statut (en attente, validé, rejeté)
  - Filtrage par date (début et fin)
- **Actions** :
  - ✅ **Valider** : Approuve le retrait et déduit du solde utilisateur
  - ❌ **Rejeter** : Refuse le retrait
- **Export** : Téléchargement des données en CSV

### 4. Historique des Investissements
- **Filtres disponibles** :
  - Recherche par nom, prénom ou téléphone
  - Filtrage par niveau d'investissement
  - Filtrage par date (début et fin)
- **Informations affichées** :
  - Utilisateur (nom, prénom, téléphone)
  - Niveau d'investissement
  - Montant investi
  - Date d'investissement
- **Export** : Téléchargement des données en CSV

### 5. Gestion des Utilisateurs
- **Filtres disponibles** :
  - Recherche par nom, prénom ou téléphone
- **Informations affichées** :
  - Données personnelles
  - Solde actuel
  - Nombre total de dépôts
  - Nombre total de retraits
  - Nombre total d'investissements
  - Date d'inscription

## Sécurité

### Vérifications d'accès
- Seuls les utilisateurs avec le rôle `admin` peuvent accéder à l'interface
- Vérification de session à chaque action
- Validation des permissions pour chaque opération

### Validation des données
- Vérification de l'existence des enregistrements avant modification
- Transactions SQL pour garantir l'intégrité des données
- Gestion des erreurs avec rollback automatique

## Architecture technique

### Fichiers principaux
- `app/admin_controller.php` : Contrôleur principal de l'administration
- `src/admin.php` : Interface utilisateur de l'administration
- `src/admin_actions.php` : Gestion des actions AJAX
- `src/admin_export.php` : Export des données en CSV

### Base de données
- **Table `utilisateurs`** : Gestion des rôles (user, admin, moderator)
- **Table `depots`** : Suivi des dépôts avec statuts
- **Table `retraits`** : Suivi des retraits avec statuts
- **Table `investissements`** : Historique des investissements
- **Table `balances`** : Soldes des utilisateurs

### Technologies utilisées
- **Backend** : PHP 7.4+ avec PDO
- **Frontend** : HTML5, CSS3 (Tailwind CSS), JavaScript
- **Base de données** : MySQL/MariaDB
- **Graphiques** : Chart.js pour les visualisations

## Utilisation

### Première connexion
1. Accédez à `app.php`
2. Connectez-vous avec les identifiants administrateur
3. Cliquez sur le bouton "Administration" dans le header
4. Vous accédez au dashboard d'administration

### Validation d'un dépôt
1. Allez dans la section "Dépôts"
2. Identifiez le dépôt en attente
3. Cliquez sur "Valider" (✅)
4. Confirmez l'action
5. Le solde de l'utilisateur est automatiquement crédité

### Validation d'un retrait
1. Allez dans la section "Retraits"
2. Identifiez le retrait en attente
3. Cliquez sur "Valider" (✅)
4. Confirmez l'action
5. Le montant est automatiquement déduit du solde utilisateur

### Export des données
1. Dans chaque section, cliquez sur "Exporter CSV"
2. Les données sont téléchargées avec les filtres appliqués
3. Le fichier contient toutes les informations de la table

## Maintenance

### Ajout d'un nouvel administrateur
```sql
UPDATE utilisateurs 
SET role = 'admin' 
WHERE telephone = 'NUMERO_TELEPHONE';
```

### Vérification des permissions
```sql
SELECT nom, prenom, telephone, role 
FROM utilisateurs 
WHERE role = 'admin';
```

### Monitoring des actions
- Toutes les actions sont tracées dans les logs PHP
- Les erreurs sont enregistrées automatiquement
- Vérifiez les logs en cas de problème

## Support

Pour toute question ou problème :
1. Vérifiez les logs d'erreur PHP
2. Contrôlez les permissions de la base de données
3. Vérifiez que l'utilisateur a bien le rôle `admin`
4. Testez la connexion à la base de données

---

**Version** : 1.0  
**Dernière mise à jour** : <?php echo date('d/m/Y'); ?>  
**Développé pour** : SMART-FAST
