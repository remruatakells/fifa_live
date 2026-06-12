# FIFA Live

Laravel webpage for a Castr HLS live stream. Viewers do not log in; they enter a viewer API key, name, mobile number, and optional email before the player opens. Each valid entry is stored in the `visitors` table.

## Setup

Install dependencies:

```bash
composer install
```

Create a MySQL database:

```sql
CREATE DATABASE fifa_live CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Set your MySQL credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fifa_live
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

Set the Castr hosted player URL in `.env`:

```env
CASTR_PLAYER_URL=https://player.castr.com/live_0c20d4f0666e11f1a569db35c9fe0782
ADMIN_ACCESS_TOKEN=change-this-admin-token
USER_ACCESS_API_KEY=change-this-user-api-key
```

`ADMIN_ACCESS_TOKEN` opens the admin page. `USER_ACCESS_API_KEY` is the key viewers must enter before they can watch.

If config is cached after changing `.env`, clear it:

```bash
php artisan config:clear
```

Run locally:

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Routes

- `GET /` - visitor detail form
- `POST /watch` - validates the viewer API key and stores visitor details
- `GET /live` - Castr player page
- `POST /leave` - clears the visitor session and returns to the form
- `GET /admin/login` - admin API-token entry page
- `GET /admin/visitors` - visitor records and block controls after admin access
- `POST /admin/visitors/{visitor}/block` - block every record with the same mobile number
- `POST /admin/visitors/{visitor}/unblock` - unblock every record with the same mobile number

## Visitor Records

Records are stored in MySQL in the `visitors` table. Stored fields:

- `name`
- `mobile`
- `email`
- `api_key_hash`
- `api_key_suffix`
- `ip_address`
- `user_agent`
- `is_blocked`
- `blocked_at`
- `block_reason`
- `last_seen_at`

Run tests:

```bash
php artisan test
```
# fifa_live
