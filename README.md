# api-payments

# 📦 Backend Laravel – Gestion des Paiements

Ce projet est le backend de l’application Flutter (web & mobile) permettant à une société de gérer ses paiements réguliers (électricité, internet, eau, etc.). Il expose une API REST sécurisée pour l’authentification, la gestion des paiements et le tableau de bord.

---

## 🚀 Fonctionnalités

- Authentification par email/mot de passe
- Création et historique des paiements
- Upload de justificatifs (PDF/image)
- API simulée pour le traitement des paiements
- Filtres par date (jour/mois/année)
- (Bonus) JWT pour sécuriser les endpoints

---

## 🧰 Stack technique

| Composant       | Technologie        |
|-----------------|--------------------|
| Framework       | Laravel 12         |
| Base de données | MySQL   |
| Authentification| Laravel Sanctum ou JWT |
| Déploiement     | Render / AWS / OVH |

---

## ⚙️ Installation locale

### Cloner le projet

```bash
git clone https://github.com/aliou9sow6/api-payments.git
cd backend-paiements

1) Installer les dépendances
composer install

2) Configurer l’environnement

Copiez .env.example en .env puis adaptez :

APP_NAME=Paiements
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paiements
DB_USERNAME=root
DB_PASSWORD=secret

# (Option) Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_DOMAIN=localhost

# (Option) JWT
JWT_SECRET=changeme
JWT_TTL=120

3) Générer la clé d’application
php artisan key:generate

4) Migrer la base & données de test
php artisan migrate --seed

5) Lancer le serveur de dev
php artisan serve

🔐 Authentification

POST /api/register : inscription

POST /api/login : connexion

Le token (Sanctum ou JWT) est renvoyé et doit être mis dans les headers des requêtes suivantes :

Authorization: Bearer <token>
Accept: application/json
