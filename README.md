# LaboSuite — Logiciel de gestion de laboratoire médical

Application web développée avec **Laravel 11** (backend + vues Blade) conformément au cahier des
charges : gestion des patients, des demandes d'analyses, des prélèvements, de la saisie des
résultats, de la validation médicale et des comptes-rendus PDF.

Frontend : Blade + Tailwind CSS (CDN, aucun build npm requis) + Alpine.js + Chart.js.
Aucune étape `npm run build` n'est nécessaire : ouvrez la page, tout se charge depuis le CDN.

---

## 1. Prérequis

- PHP >= 8.2 avec les extensions habituelles (pdo_sqlite ou pdo_mysql, mbstring, openssl, curl, gd)
- Composer
- Un serveur MySQL **si** vous ne souhaitez pas utiliser SQLite (SQLite est activé par défaut,
  aucune base de données à installer pour démarrer)

Sous Windows, l'environnement le plus simple est **Laragon** ou **XAMPP** (ils fournissent déjà
PHP + Composer + MySQL). Sous Mac/Linux, installez PHP et Composer via votre gestionnaire de
paquets habituel.

## 2. Installation

```bash
# 1. Se placer dans le dossier du projet
cd gestion-labo

# 2. Installer les dépendances PHP (télécharge le framework Laravel + DomPDF)
composer install

# 3. Copier le fichier d'environnement et générer la clé d'application
cp .env.example .env
php artisan key:generate

# 4. Base de données : SQLite est utilisé par défaut, le fichier existe déjà
#    (database/database.sqlite). Il suffit de lancer les migrations :
php artisan migrate --seed

# 5. Lancer le serveur de développement
php artisan serve
```

Ouvrez ensuite **http://localhost:8000** dans votre navigateur.

> Si `database/database.sqlite` n'existe pas ou a été supprimé, recréez-le avec :
> `touch database/database.sqlite` (Mac/Linux) ou en créant un fichier vide de ce nom sous
> Windows, puis relancez `php artisan migrate --seed`.

### Utiliser MySQL à la place de SQLite

Dans `.env`, remplacez :
```
DB_CONNECTION=sqlite
```
par :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_labo
DB_USERNAME=root
DB_PASSWORD=
```
Créez la base `gestion_labo` dans phpMyAdmin/MySQL puis relancez `php artisan migrate --seed`.

## 3. Comptes de démonstration

Le seeder crée automatiquement un compte par rôle (mot de passe identique pour tous : `password`) :

| Rôle | E-mail |
| --- | --- |
| Administrateur | admin@labo.test |
| Réceptionniste | reception@labo.test |
| Technicien de laboratoire | technicien@labo.test |
| Responsable médical / Biologiste | biologiste@labo.test |

## 4. Fonctionnement du workflow

1. **Réceptionniste** — enregistre le patient, puis crée une *demande d'analyse* en choisissant
   les examens souhaités dans le catalogue.
2. **Technicien** — enregistre le *prélèvement* (type d'échantillon, date), puis saisit les
   *résultats* pour chaque analyse demandée.
3. **Biologiste** — valide les résultats saisis ; une fois validée, la demande peut générer un
   *compte-rendu PDF* téléchargeable/imprimable.
4. **Administrateur** — gère les comptes utilisateurs et leurs rôles, et a accès à tout.

Le tableau de bord centralise le nombre de patients, de demandes, l'activité des 7 derniers
jours et la répartition des demandes par statut.

## 5. Structure du projet

```
app/Http/Controllers/   Contrôleurs (Auth, Dashboard, Patient, Examen, DemandeAnalyse,
                         Prelevement, Resultat, Rapport, User)
app/Http/Middleware/     RoleMiddleware.php — contrôle d'accès par rôle
app/Models/              User, Patient, Examen, DemandeAnalyse, DemandeExamen,
                         Prelevement, Resultat
database/migrations/     Schéma de la base de données
database/seeders/        Données de démonstration (utilisateurs, catalogue, patients)
resources/views/         Vues Blade (Tailwind CDN + Alpine.js + Chart.js)
resources/views/components/  layout.blade.php, badge-statut, progress-tube, stat-card...
routes/web.php           Toutes les routes de l'application
```

## 6. Évolutions possibles (section 7 du cahier des charges)

Le code est structuré pour accueillir facilement : gestion des stocks de réactifs, facturation,
notifications SMS, portail patient, application mobile, gestion multi-sites, intégration
d'équipements médicaux.

## 7. Dépannage

- **Page blanche / erreur 500** : vérifiez que `APP_KEY` est bien généré (`php artisan key:generate`)
  et que `storage/` et `bootstrap/cache/` sont accessibles en écriture.
- **"could not find driver" (SQLite)** : activez l'extension PHP `pdo_sqlite` dans `php.ini`.
- **Styles non appliqués / icônes manquantes** : l'application charge Tailwind, Alpine.js, Chart.js
  et les icônes Lucide depuis un CDN — une connexion internet est nécessaire pour l'affichage.
