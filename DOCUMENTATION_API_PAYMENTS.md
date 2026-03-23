# 📋 Documentation API Payments - Guide Complet

## 📖 Table des Matières

1. [Vue d'ensemble](#vue-densemble)
2. [Prérequis](#prérequis)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Structure de la Base de Données](#structure-de-la-base-de-données)
6. [Authentification](#authentification)
7. [Endpoints API](#endpoints-api)
8. [Tests avec PaymentSeeder](#tests-avec-paymentseeder)
9. [Exemples d'utilisation](#exemples-dutilisation)
10. [Gestion des Erreurs](#gestion-des-erreurs)
11. [Déploiement](#déploiement)

---

## 🎯 Vue d'ensemble

Cette API REST Laravel permet de gérer un système de paiements avec les fonctionnalités suivantes :

- **Authentification sécurisée** avec Laravel Sanctum
- **Gestion des paiements** (CRUD complet)
- **Paiements récurrents** 
- **Upload de justificatifs** (PDF/images)
- **Filtrage par dates**
- **Tableau de bord** avec statistiques
- **Documentation Swagger** intégrée

### 🧰 Stack Technique

| Composant | Technologie |
|-----------|-------------|
| Framework | Laravel 12 |
| Base de données | MySQL |
| Authentification | Laravel Sanctum |
| Documentation | Swagger/OpenAPI |
| Upload fichiers | Laravel Storage |

---

## ⚙️ Prérequis

Avant l'installation, assurez-vous d'avoir :

- **PHP** >= 8.2
- **Composer** >= 2.0
- **MySQL** >= 8.0 ou **MariaDB** >= 10.3
- **Node.js** >= 16 (pour les assets front-end)
- **Git**

---

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/aliou9sow6/api-payments.git
cd api-payments
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances Node.js

```bash
npm install
```

### 4. Configuration de l'environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

---

## ⚙️ Configuration

### Configuration de la base de données

Éditez le fichier `.env` avec vos paramètres de base de données :

```env
APP_NAME="API Payments"
APP_ENV=local
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_payments
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Configuration Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:3000
SESSION_DOMAIN=localhost

# Configuration des fichiers
FILESYSTEM_DISK=local
```

### Créer la base de données

```sql
CREATE DATABASE api_payments CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Exécuter les migrations

```bash
php artisan migrate
```

### Créer le lien symbolique pour le stockage

```bash
php artisan storage:link
```

---

## 🗄️ Structure de la Base de Données

### Table `users`
```sql
- id (bigint, primary key)
- name (varchar)
- email (varchar, unique)
- password (varchar)
- role (enum: 'user', 'admin') - défaut: 'user'
- email_verified_at (timestamp, nullable)
- remember_token (varchar, nullable)
- created_at, updated_at (timestamps)
```

### Table `payments`
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users.id)
- amount (decimal 10,2)
- description (varchar, nullable)
- currency (varchar 3) - ex: 'XOF', 'USD', 'EUR'
- payment_method (enum: 'credit_card', 'paypal', 'bank_transfer', 'crypto', 'Diokopay')
- proof (text, nullable) - chemin vers le justificatif
- status (varchar) - ex: 'pending', 'completed', 'failed'
- created_at, updated_at (timestamps)
```

### Table `recurring_payments`
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users.id)
- amount (decimal 10,2)
- currency (varchar 3)
- payment_method (enum: 'credit_card', 'paypal', 'bank_transfer', 'crypto', 'Diokopay')
- status (varchar) - ex: 'active', 'inactive', 'paused', 'cancelled'
- start_date (date, nullable)
- end_date (date, nullable)
- frequency (enum: 'daily', 'weekly', 'monthly', 'quarterly', 'yearly')
- created_at, updated_at (timestamps)
```

---

## 🔐 Authentification

L'API utilise **Laravel Sanctum** pour l'authentification par tokens.

### Workflow d'authentification

1. **Inscription/Connexion** → Récupération du token
2. **Ajout du token** dans les headers des requêtes suivantes
3. **Accès aux endpoints protégés**

### Headers requis pour les requêtes authentifiées

```http
Authorization: Bearer VOTRE_TOKEN_ICI
Accept: application/json
Content-Type: application/json
```

---

## 🛠️ Endpoints API

### 🔑 Authentification

#### POST `/api/register` - Inscription
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Réponse (201):**
```json
{
  "message": "User registered successfully!",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user"
  },
  "token": "1|abc123def456..."
}
```

#### POST `/api/login` - Connexion
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

#### POST `/api/logout` - Déconnexion
*Requête authentifiée - Aucun body requis*

### 📊 Dashboard

#### GET `/api/dashboard` - Statistiques utilisateur
*Requête authentifiée*

**Réponse (200):**
```json
{
  "success": true,
  "totalPaid": 15750.50,
  "totalPending": 3,
  "recentPayments": [...]
}
```

### 💳 Paiements

#### GET `/api/payments` - Liste des paiements
*Requête authentifiée avec pagination*

**Paramètres optionnels:**
- `page` (int) - Numéro de page

#### POST `/api/payments` - Créer un paiement
*Requête authentifiée*

```json
{
  "amount": 25000.00,
  "description": "Facture électricité janvier 2024",
  "currency": "XOF",
  "payment_method": "Diokopay",
  "status": "pending"
}
```

**Avec fichier (multipart/form-data):**
- `proof` (file) - Justificatif (jpg, jpeg, png, pdf, max 2MB)

#### GET `/api/payments/{id}` - Détails d'un paiement
*Requête authentifiée*

#### PUT `/api/payments/{id}` - Modifier un paiement
*Requête authentifiée*

#### DELETE `/api/payments/{id}` - Supprimer un paiement
*Requête authentifiée*

#### GET `/api/payments/filter/past` - Filtrer les paiements passés
*Requête authentifiée*

**Paramètres:**
- `date` (date) - Date limite (format: Y-m-d)

#### GET `/api/payments/filter/date-range` - Filtrer par période
*Requête authentifiée*

**Paramètres:**
- `start_date` (date) - Date de début
- `end_date` (date) - Date de fin

### 🔄 Paiements Récurrents

#### GET `/api/recurring-payments` - Liste des paiements récurrents
*Requête authentifiée*

#### POST `/api/recurring-payments` - Créer un paiement récurrent
*Requête authentifiée*

```json
{
  "amount": 15000.00,
  "status": "active",
  "start_date": "2024-01-01",
  "end_date": "2024-12-31",
  "frequency": "monthly",
  "payment_method": "bank_transfer"
}
```

#### GET `/api/recurring-payments/{id}` - Détails d'un paiement récurrent
#### PUT `/api/recurring-payments/{id}` - Modifier un paiement récurrent
#### DELETE `/api/recurring-payments/{id}` - Supprimer un paiement récurrent

---

## 🧪 Tests avec PaymentSeeder

### Exécuter le seeder

```bash
# Exécuter les migrations et seeders
php artisan migrate:fresh --seed

# Ou exécuter seulement le PaymentSeeder
php artisan db:seed --class=PaymentSeeder
```

### Données de test créées

Le `PaymentSeeder` crée automatiquement :

#### 👥 Utilisateurs de test
```
1. Admin:
   - Nom: Gabriel AKAKE
   - Email: testeur.admin@dioko.sn
   - Mot de passe: password
   - Rôle: admin

2. Utilisateur:
   - Nom: Aliou SOW
   - Email: testeur.user@dioko.sn
   - Mot de passe: password
   - Rôle: user
```

#### 💳 Paiements de test
- **10 paiements** générés pour l'utilisateur test
- **Montants aléatoires** entre 5 000 et 30 000
- **Devises variées** : XOF, USD, EUR
- **Méthodes de paiement** : credit_card, paypal, bank_transfer, diokopay
- **Statuts variés** : completed, pending, failed
- **Dates étalées** sur les 30 derniers jours
- **Justificatifs simulés** : proofs/facture_1.pdf, etc.

### Tester l'API avec les données du seeder

#### 1. Se connecter avec un utilisateur test

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "testeur.user@dioko.sn",
    "password": "password"
  }'
```

#### 2. Utiliser le token retourné

```bash
# Remplacez TOKEN_ICI par le token reçu
curl -X GET http://localhost:8000/api/payments \
  -H "Authorization: Bearer TOKEN_ICI" \
  -H "Accept: application/json"
```

#### 3. Tester le dashboard

```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer TOKEN_ICI" \
  -H "Accept: application/json"
```

---

## 🔧 Exemples d'utilisation

### Exemple complet avec cURL

#### 1. Inscription d'un nouvel utilisateur

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Marie Dupont",
    "email": "marie@example.com",
    "password": "motdepasse123",
    "password_confirmation": "motdepasse123"
  }'
```

#### 2. Créer un paiement avec justificatif

```bash
curl -X POST http://localhost:8000/api/payments \
  -H "Authorization: Bearer VOTRE_TOKEN" \
  -H "Accept: application/json" \
  -F "amount=50000" \
  -F "description=Facture eau février 2024" \
  -F "currency=XOF" \
  -F "payment_method=Diokopay" \
  -F "status=pending" \
  -F "proof=@/chemin/vers/facture.pdf"
```

#### 3. Filtrer les paiements par période

```bash
curl -X GET "http://localhost:8000/api/payments/filter/date-range?start_date=2024-01-01&end_date=2024-01-31" \
  -H "Authorization: Bearer VOTRE_TOKEN" \
  -H "Accept: application/json"
```

### Exemple avec JavaScript/Fetch

```javascript
// Configuration de base
const API_BASE = 'http://localhost:8000/api';
let authToken = '';

// Fonction de connexion
async function login(email, password) {
  const response = await fetch(`${API_BASE}/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  if (data.token) {
    authToken = data.token;
  }
  return data;
}

// Fonction pour récupérer les paiements
async function getPayments() {
  const response = await fetch(`${API_BASE}/payments`, {
    headers: {
      'Authorization': `Bearer ${authToken}`,
      'Accept': 'application/json'
    }
  });
  
  return await response.json();
}

// Utilisation
login('testeur.user@dioko.sn', 'password')
  .then(() => getPayments())
  .then(payments => console.log(payments));
```

---

## ⚠️ Gestion des Erreurs

### Codes de statut HTTP

| Code | Signification |
|------|---------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 400 | Requête malformée |
| 401 | Non authentifié |
| 403 | Accès interdit |
| 404 | Ressource non trouvée |
| 422 | Erreur de validation |
| 500 | Erreur serveur |

### Format des erreurs

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Erreurs courantes

#### Erreur d'authentification (401)
```json
{
  "message": "Unauthenticated."
}
```

#### Erreur de validation (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "amount": ["The amount field is required."]
  }
}
```

---

## 🚀 Déploiement

### Lancer le serveur de développement

```bash
php artisan serve
```

L'API sera accessible sur `http://localhost:8000`

### Documentation Swagger

Accédez à la documentation interactive sur :
```
http://localhost:8000/api/documentation
```

### Configuration pour la production

1. **Optimiser l'application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

2. **Variables d'environnement**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
```

3. **Configuration HTTPS**
```env
SANCTUM_STATEFUL_DOMAINS=votre-domaine.com
SESSION_SECURE_COOKIE=true
```

---

## 📝 Notes importantes

### Sécurité
- Les tokens Sanctum expirent automatiquement, aprés 60 munites
- Les mots de passe sont hashés avec Hash
- Les uploads de fichiers sont validés (type et taille)
- Les autorisations sont gérées par les Policies Laravel

### Performance
- Pagination automatique sur les listes
- Index sur les clés étrangères
- Cache des configurations en production

### Maintenance
- Logs automatiques dans `storage/logs/`
- Sauvegarde régulière de la base de données recommandée
- Monitoring des performances conseillé

---

## 🆘 Support et Dépannage

### Problèmes courants

#### "Token Mismatch" ou "Unauthenticated"
- Vérifiez que le token est bien inclus dans le header `Authorization`
- Assurez-vous que le token n'a pas expiré
- Vérifiez la configuration SANCTUM_STATEFUL_DOMAINS

#### Erreurs de base de données
- Vérifiez la connexion à MySQL
- Assurez-vous que les migrations ont été exécutées
- Vérifiez les permissions de l'utilisateur de base de données

#### Problèmes d'upload de fichiers
- Vérifiez que `storage:link` a été exécuté
- Contrôlez les permissions du dossier `storage/`
- Vérifiez la configuration `upload_max_filesize` de PHP

### Commandes utiles

```bash
# Vérifier la configuration
php artisan config:show

# Nettoyer les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Vérifier les routes
php artisan route:list

# Tester la connexion à la base
php artisan tinker
>>> DB::connection()->getPdo();
```

---

*Documentation de l'API Payments v1.0*
