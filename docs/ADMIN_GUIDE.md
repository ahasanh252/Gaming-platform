# Admin Panel Architecture

## Overview

The admin panel provides comprehensive management of the gaming platform with role-based access control, transaction management, user management, and payment gateway configuration.

## Access Control

Admin access is managed through:
- **Role**: Users with `admin` role in the database
- **Middleware**: `IsAdmin` middleware protects admin routes
- **Gates**: Authorization gates defined in `AuthServiceProvider`

## Admin Modules

### 1. User Management
- List all users
- View user details and account information
- Suspend/Ban users
- Reset user passwords
- Adjust user balances
- View user activity logs

**Controller**: `Admin/UserController`
**Routes**: 
- `GET /admin/users` - List users
- `GET /admin/users/{id}` - View user
- `POST /admin/users/{id}/suspend` - Suspend user
- `POST /admin/users/{id}/ban` - Ban user
- `POST /admin/users/{id}/adjust-balance` - Adjust balance

### 2. Transaction Management
- Approve/Reject deposits
- Approve/Reject withdrawals
- View all transactions
- Filter by type, status, payment method
- Generate transaction reports

**Controller**: `Admin/TransactionController`
**Routes**:
- `GET /admin/transactions` - List transactions
- `GET /admin/transactions/{id}` - View transaction
- `POST /admin/transactions/{id}/approve` - Approve transaction
- `POST /admin/transactions/{id}/reject` - Reject transaction

### 3. Payment Gateway Management
- Configure payment methods (Bkash, Nagad, Binance, USDT)
- Set transaction limits (min/max amounts)
- Set charges (percentage and fixed)
- Manage API credentials
- Switch between sandbox/production modes
- Enable/Disable payment methods

**Controller**: `Admin/PaymentGatewayController`
**Routes**:
- `GET /admin/payment-gateways` - List gateways
- `GET /admin/payment-gateways/{id}/edit` - Edit gateway
- `POST /admin/payment-gateways/{id}` - Update gateway
- `POST /admin/payment-gateways/{id}/toggle` - Enable/Disable

### 4. Game Management
- Monitor active games
- View game statistics
- Cancel games if needed
- View player information per game
- Monitor bets and winnings

**Controller**: `Admin/GameController`
**Routes**:
- `GET /admin/games` - List games
- `GET /admin/games/{id}` - View game
- `POST /admin/games/{id}/cancel` - Cancel game

### 5. Referral Management
- View all referral relationships
- Monitor referral bonuses paid
- Approve/Reject referral bonuses
- View referral statistics

**Controller**: `Admin/ReferralController`
**Routes**:
- `GET /admin/referrals` - List referrals
- `GET /admin/referrals/stats` - Referral statistics

### 6. Reports & Analytics
- Dashboard with KPIs
- Revenue reports
- User growth statistics
- Game statistics
- Transaction reports by date range

**Controller**: `Admin/ReportController`
**Routes**:
- `GET /admin/dashboard` - Main dashboard
- `GET /admin/reports/revenue` - Revenue report
- `GET /admin/reports/users` - User report
- `GET /admin/reports/games` - Game report

### 7. Admin Settings
- Configure system-wide settings
- Manage admin users
- View audit logs
- Configure email settings

**Controller**: `Admin/SettingController`
**Routes**:
- `GET /admin/settings` - View settings
- `POST /admin/settings` - Update settings
- `GET /admin/admins` - List admin users
- `GET /admin/audit-logs` - View audit logs

## Admin Dashboard Layout

```
┌─────────────────────────────────────────────────────┐
│  Gaming Platform Admin Panel                         │
├─────────────────────────────────────────────────────┤
│ [Home] [Users] [Trans] [Games] [Payment] [Report]   │
├─────────────────────────────────────────────────────┤
│                                                      │
│  ┌──────────────────────────────────────────────┐  │
│  │ Dashboard                    [≡] Menu        │  │
│  └──────────────────────────────────────────────┘  │
│                                                      │
│  ┌─────────┐  ┌─────────┐  ┌──────────┐           │
│  │ Users   │  │ Games   │  │ Revenue  │           │
│  │ 1,234   │  │ 456     │  │ ৳ 50,000 │           │
│  └─────────┘  └─────────┘  └──────────┘           │
│                                                      │
│  ┌─────────────────────────────────────────────┐   │
│  │ Pending Transactions                        │   │
│  ├──────────┬──────────┬────────┬───────────┤   │
│  │ User     │ Type     │ Amount │ Action    │   │
│  ├──────────┼──────────┼────────┼───────────┤   │
│  │ John D   │ Withdraw │ ৳ 5000 │ [✓] [✗]  │   │
│  │ Jane S   │ Deposit  │ ৳ 2000 │ [✓] [✗]  │   │
│  └──────────┴──────────┴────────┴───────────┘   │
│                                                      │
└─────────────────────────────────────────────────────┘
```

## Three Dot Menu Features

Admin dashboard includes a three-dot menu (≡) with:

1. **Profile** - View/Edit admin profile
2. **Settings** - System settings
3. **Audit Log** - View all admin actions
4. **Reports** - Generate reports
5. **Logout** - Sign out

User dashboard also has similar three-dot menu with:

1. **Profile** - View/Edit profile
2. **Bank Accounts** - Manage withdrawal accounts
3. **Transaction History** - View all transactions
4. **Referral Info** - View referral details
5. **Settings** - Account settings
6. **Logout** - Sign out

## Security Features

- ✅ Role-based access control
- ✅ Admin action logging (AdminLog model)
- ✅ IP address tracking
- ✅ User agent tracking
- ✅ Action audit trail
- ✅ Request validation
- ✅ CSRF protection

## Audit Logging

Every admin action is logged with:
- Admin ID
- Action performed
- Module affected
- Model type and ID
- Changes made (JSON)
- IP address
- User agent
- Timestamp

Query example:
```php
// Get all approve actions by admin
AdminLog::byAdmin($adminId)
    ->byAction('approve')
    ->recent()
    ->get();
```

## Protected Routes Example

```php
// routes/admin.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    
    Route::resource('users', UserController::class);
    Route::post('users/{user}/suspend', [UserController::class, 'suspend']);
    Route::post('users/{user}/ban', [UserController::class, 'ban']);
    
    Route::resource('transactions', TransactionController::class);
    Route::post('transactions/{transaction}/approve', [TransactionController::class, 'approve']);
    Route::post('transactions/{transaction}/reject', [TransactionController::class, 'reject']);
    
    // ... more routes
});
```

## Next Steps

1. Create admin controllers for each module
2. Create admin views/templates
3. Implement authorization policies
4. Add reporting functionality
5. Set up admin notifications
6. Create admin user management interface
