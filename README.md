# FireGuard Backend

Smart **forest fire monitoring** API. Sensors, brigades, emergency requests, and mobile robots report into one Symfony backend so operators and field apps share the same live picture.

Graduation project — Damascus University · Grade **91/100** · [Obada Al Tarazi](https://github.com/obadatarazi)

---

## What it covers

| Area | Examples |
| --- | --- |
| Domain | Forests, centers, fires, fire brigades, tasks, emergency requests |
| Devices | Fixed sensors, device values, mobile sensors / car robots |
| Location | Live position + history for devices and robots |
| Access | JWT auth, admin API, mobile API, device ingest endpoints |

Built on **Symfony 6.2**, Doctrine/MySQL, Lexik JWT, FOSRest, Nelmio API Doc / CORS.

## API surfaces

```text
/api/admin/...           Operator CRUD (centers, forests, fires, brigades, devices, …)
/api/mobile/...          Field / mobile app reads & actions
/api/device-ingest/...   Device → backend location updates (device key header)
```

Admin CRUD expects form fields (`multipart/form-data` or `x-www-form-urlencoded`), not raw JSON — otherwise you get 422 validation errors. Details for robots: [`MOBILE_ROBOT_API.md`](./MOBILE_ROBOT_API.md).

## Requirements

- PHP 8.1+ with `intl`
- Composer + Node (asset build)
- MySQL
- JWT key pair under `config/jwt/` (not in the repo — generate locally)

## Setup

```bash
git clone https://github.com/obadatarazi/fireguard_backend.git
cd fireguard_backend
composer install
npm install && npm run build
cp .env.dist .env.local
# set DATABASE_URL and JWT paths in .env.local
php bin/console doctrine:migrations:migrate --no-interaction
# optional demo data if fixtures are configured:
# php bin/console doctrine:fixtures:load
```

Dev server:

```bash
symfony server:start
# or: php -S 127.0.0.1:8000 -t public
```

Async jobs (if Messenger is configured):

```bash
php bin/console messenger:consume async --limit=5 -vv
```

## Tests

```bash
php bin/phpunit
```

## Security checklist

- Never commit `.env` / `.env.local` or JWT private keys
- Rotate any device ingest keys if they were shared in docs or chat
- Keep admin JWT secrets out of public forks

## Related

Wireless sensor network concept: [FireGuard](https://github.com/obadatarazi/FireGuard) (upstream / concept repo).
