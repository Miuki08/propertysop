kena spil dikit jir

# Masuk ke folder kerja
cd /path/ke/folder-kerja

# Buat project Laravel 10/11 (contoh)
composer create-project laravel/laravel:^10.0 bengkel-app
cd bengkel-app

# set .env dulu kids
php artisan key:generate
php artisan migrate

composer require filament/filament:"^3.3" -W
php artisan filament:install --panels

php artisan make:filament-user

php artisan make:filament-panel mechanic

php artisan make:model Customer -m
php artisan make:model Motor -m
php artisan make:model Sparepart -m
php artisan make:model Booking -m
php artisan make:model Purchase -m

php artisan migrate

php artisan make:filament-resource Customer --generate
php artisan make:filament-resource Motor --generate
php artisan make:filament-resource Sparepart --generate
php artisan make:filament-resource Booking --generate
php artisan make:filament-resource Purchase --generate

php artisan make:filament-resource Booking --panel=admin --generate
php artisan make:filament-resource Booking --panel=mechanic --generate

// app/Providers/Filament/AdminPanelProvider.php
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->path('admin')
        ->login()
        ->topNavigation() // kalau kamu pakai menu di header
        // dll...
}

// app/Providers/Filament/MechanicPanelProvider.php
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('mechanic')
        ->path('mechanic')
        ->login()
        // dll...
}

# Bersihkan cache config / route / view
php artisan optimize:clear

# Generate ulang autoload composer
composer dump-autoload

# Jalankan ulang migration dari nol (kalau lagi revisi banyak)
php artisan migrate:fresh --seed

link untuk lu AI

https://www.perplexity.ai/search/cara-cek-version-php-yang-ada-NgQ2n6JhTTK7t7LuZBxOBw#58