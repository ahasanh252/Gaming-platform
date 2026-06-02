## Installation & Setup Guide

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel gaming-platform
cd gaming-platform
```

### Step 2: Copy Repository Files

```bash
# Copy all files from the GitHub repository
git clone https://github.com/ahasanh252/Gaming-platform.git .
```

### Step 3: Install Dependencies

```bash
# PHP dependencies
composer install

# Install Sanctum for API authentication
composer require laravel/sanctum

# Node dependencies for frontend assets
npm install
```

### Step 4: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Update .env file with your database credentials
# DB_HOST=127.0.0.1
# DB_DATABASE=gaming_platform
# DB_USERNAME=root
# DB_PASSWORD=
```

### Step 5: Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE gaming_platform;"

# Run migrations
php artisan migrate

# Seed initial data (optional)
php artisan db:seed
```

### Step 6: Publish Sanctum Configuration

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Step 7: Create Storage Link

```bash
php artisan storage:link
```

### Step 8: Build Assets

```bash
npm run build
```

### Step 9: Start Development Server

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start queue listener (for jobs)
php artisan queue:listen

# Terminal 3: Build assets (watch mode)
npm run dev
```

Application will be available at: `http://localhost:8000`

---

## Database Configuration

Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gaming_platform
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## API Authentication Setup

Add to `app/Providers/AuthServiceProvider.php`:

```php
public function boot(): void
{
    $this->registerPolicies();
    
    // API rate limiting
    Sanctum::tokensCan([
        'read',
        'create',
        'update',
        'delete',
    ]);
}
```

---

## Testing Installation

### Test Web Routes

1. Visit `http://localhost:8000` - Should redirect to login
2. Click "Register" - Test registration page
3. Create account with referral code
4. Login - Should see dashboard

### Test API Endpoints

```bash
# Register (get token)
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password",
    "password_confirmation": "password"
  }'

# Get wallet balance
curl -X GET http://localhost:8000/api/v1/wallet/balance \
  -H "Authorization: Bearer {token}"

# List games
curl -X GET http://localhost:8000/api/v1/games \
  -H "Authorization: Bearer {token}"
```

---

## File Structure Overview

```
gaming-platform/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                 # Authentication
│   │   │   ├── Admin/                # Admin panel
│   │   │   ├── User/                 # User controllers
│   │   │   └── Game/                 # Game controllers
│   │   └── Middleware/               # Custom middleware
│   ├── Models/                       # Eloquent models
│   └── Providers/                    # Service providers
├── database/
│   ├── migrations/                   # Database tables
│   └── seeders/                      # Demo data
├── resources/
│   ├── views/                        # Blade templates
│   │   ├── auth/
│   │   ├── user/
│   │   └── admin/
│   ├── css/                          # Stylesheets
│   └── js/                           # JavaScript
├── routes/
│   ├── web.php                       # Web routes
│   └── api.php                       # API routes
├── config/
│   ├── gaming.php                    # Gaming settings
│   └── ...                           # Other configs
├── .env.example                      # Environment template
└── composer.json                     # PHP dependencies
```

---

## Configuration Files

### Main Configuration Files to Update

1. **`config/gaming.php`** - Gaming platform settings
2. **`config/app.php`** - Application name and settings
3. **`config/mail.php`** - Email configuration
4. **`config/database.php`** - Database settings

### Environment Variables

```env
# App
APP_NAME="Gaming Platform"
APP_ENV=local
APP_DEBUG=true

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=gaming_platform
DB_USERNAME=root
DB_PASSWORD=

# Mail (for password reset)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@gamingplatform.com"

# Payment Gateways (configure later)
BKASH_MODE=sandbox
NAGAD_MODE=sandbox
BINANCE_TESTNET=true

# Gaming
GAME_MIN_BET=100
GAME_MAX_BET=10000
MINIMUM_DEPOSIT=100
MINIMUM_WITHDRAWAL=500
REFERRAL_BONUS=100
```

---

## Troubleshooting

### Issue: "php artisan" command not found

```bash
# Use PHP explicitly
php artisan migrate
```

### Issue: Database connection error

```bash
# Check MySQL is running
mysql -u root -p

# Update .env credentials
php artisan cache:clear
php artisan config:clear
```

### Issue: Permission denied on storage folder

```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: Tables already exist

```bash
# Fresh migration (WARNING: deletes all data)
php artisan migrate:fresh

# Or rollback then migrate
php artisan migrate:rollback
php artisan migrate
```

### Issue: Sanctum token not working

```bash
# Republish Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force

# Clear cache
php artisan cache:clear
```

---

## Next Steps After Installation

1. ✅ Install dependencies
2. ✅ Configure database
3. ✅ Run migrations
4. ✅ Test login/registration
5. ⬜ Create admin user (see below)
6. ⬜ Configure payment gateways
7. ⬜ Implement game logic
8. ⬜ Set up email notifications
9. ⬜ Configure production deployment

---

## Create Admin User

Run this command to create a test admin:

```bash
php artisan tinker

# In Tinker shell:
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'refer_code' => 'ADMIN' . strtoupper(substr(md5(time()), 0, 4)),
    'role' => 'admin',
    'status' => 'active'
]);

exit
```

Then login with:
- Email: `admin@example.com`
- Password: `password`

---

## Support & Documentation

- **API Docs**: See `docs/API.md`
- **Database Schema**: See `docs/DATABASE.md`
- **Admin Guide**: See `docs/ADMIN_GUIDE.md`
- **Laravel Docs**: https://laravel.com/docs
