<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
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
>>>>>>> a241531b9d2d115d6e3fc1411b024ca750daa7af
