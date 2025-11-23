# Hotel-Project-CSC-540
Hotel App
## Local Setup
1. Copy `config.example.php` → `config.php` (do NOT commit).
2. Create MySQL DB `hotelapp` in phpMyAdmin.
3. Run schema + migration SQL (see `/sql/` folder).
4. Start XAMPP (Apache + MySQL).
5. Visit `http://localhost/Hotel-Project-CSC-540/`.

## SQL Folder
The `sql` folder contains the following subfolders:
- `migrations`: Contains SQL files for creating and modifying the database schema.

To set up the database, run the `schema.sql` file first, then run the migration files in order.

## Secrets
- Never commit `config.php` or `.env` (see `.gitignore`).
- Use Stripe **test** keys locally.
