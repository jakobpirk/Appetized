# Appetized

Laravel-backend med Vue-frontend til at administrere og vise en menukort-API.

- REST API til menu-poster på `/api/menu-items` (CRUD).
- Adminpanel på `/admin` med formular til oprettelse, redigering og sletning.
- Offentlig forside, der læser data fra API'et.

## Kom i gang
1. Kopiér miljøfil: `cp .env.example .env`.
2. Installer PHP-afhængigheder: `composer install`.
3. Installer frontend-afhængigheder: `npm install`.
4. Kør migreringer (SQLite er forudopsat): `php artisan migrate`.
5. Start udvikling: `npm run dev` og `php artisan serve`.

## Tests
- Backend: `php artisan test`
- Byg assets: `npm run build`
