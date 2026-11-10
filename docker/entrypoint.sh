#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

# Sync runtime DB settings into .env for artisan
php -r '
$env = file_get_contents(".env");
$map = [
  "APP_NAME" => getenv("APP_NAME") ?: "HvacCaseTrack",
  "APP_ENV" => getenv("APP_ENV") ?: "local",
  "APP_DEBUG" => getenv("APP_DEBUG") ?: "true",
  "APP_URL" => getenv("APP_URL") ?: "http://localhost:8112",
  "DB_CONNECTION" => getenv("DB_CONNECTION") ?: "mysql",
  "DB_HOST" => getenv("DB_HOST") ?: "mysql",
  "DB_PORT" => getenv("DB_PORT") ?: "3306",
  "DB_DATABASE" => getenv("DB_DATABASE") ?: "hvac_case_track",
  "DB_USERNAME" => getenv("DB_USERNAME") ?: "hct",
  "DB_PASSWORD" => getenv("DB_PASSWORD") ?: "secret",
  "CACHE_STORE" => getenv("CACHE_STORE") ?: "database",
  "QUEUE_CONNECTION" => getenv("QUEUE_CONNECTION") ?: "database",
  "SESSION_DRIVER" => getenv("SESSION_DRIVER") ?: "database",
];
foreach ($map as $k => $v) {
  if (preg_match("/^{$k}=.*/m", $env)) {
    $env = preg_replace("/^{$k}=.*/m", $k."=".$v, $env);
  } else {
    $env .= "\n{$k}={$v}";
  }
}
file_put_contents(".env", $env);
'

if ! grep -qE '^APP_KEY=base64:.+' .env; then
  php artisan key:generate --force
fi

echo "Waiting for MySQL..."
for i in $(seq 1 60); do
  if php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".(getenv("DB_PORT")?:3306).";dbname=".getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); echo "ok"; } catch (Throwable $e) { exit(1);}'; then
    echo "MySQL is ready."
    break
  fi
  sleep 2
done

php artisan migrate --force
php artisan db:seed --force
php artisan filament:assets || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec "$@"
