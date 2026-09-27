# Quick Reference Guide

## 🚀 Quick Start Commands

```bash
# Install & Setup (5 minutes)
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

# Access at: http://localhost:8000
# Login: (create admin user via tinker)
```

## 📂 Important Files

| File | Purpose |
|------|---------|
| `app/Models/*.php` | Database models (7 models) |
| `app/Http/Controllers/*.php` | API controllers (6 controllers) |
| `database/migrations/` | Database schemas (7 migrations) |
| `resources/views/` | Blade templates |
| `routes/web.php` | All routes and endpoints |
| `config/audit.php` | Audit configuration |
| `.env.example` | Environment template |

## 🗂️ Main Routes

### Public
- `/` → Redirect to login
- `/login` → Login page
- `POST /login` → Process login

### Dashboard
- `/dashboard` → Dashboard view
- `GET /api/dashboard/stats` → Statistics JSON

### LAN Ports
- `GET /lan-ports` → List view with filters
- `GET /lan-ports/create` → Create form
- `POST /lan-ports` → Create port
- `GET /lan-ports/{id}` → View port
- `GET /lan-ports/{id}/edit` → Edit form
- `PUT /lan-ports/{id}` → Update port
- `DELETE /lan-ports/{id}` → Delete port
- `GET /lan-ports/export/csv` → Export CSV
- `POST /lan-ports/bulk/status` → Bulk update

### Voice Ports
- `GET /voice-ports` → List view
- `GET /voice-ports/create` → Create form
- `POST /voice-ports` → Create
- Similar CRUD endpoints as LAN ports

### Server Racks
- `GET /server-racks` → List racks
- `GET /server-racks/create` → Create form
- `POST /server-racks` → Create rack
- `GET /server-racks/{id}` → Rack details
- `GET /server-racks/{id}/visualize` → 3D visualization
- `GET /server-racks/{id}/api/visualization` → Visualization data
- `POST /server-racks/{id}/equipment` → Add equipment
- `PUT /server-racks/unit/{id}` → Update unit
- `DELETE /server-racks/unit/{id}` → Remove equipment

### Audit Logs (Admin Only)
- `GET /audit-logs` → List with filters
- `GET /audit-logs/{id}` → View details
- `GET /audit-logs/export/csv` → Export CSV
- `GET /audit-logs/statistics/view` → Statistics
- `POST /audit-logs/purge` → Purge old logs

## 🔧 Database Operations

### Create Tables
```bash
php artisan migrate
```

### Rollback Last Migration
```bash
php artisan migrate:rollback
```

### Reset All
```bash
php artisan migrate:reset
php artisan migrate
```

### Create Admin User (via Tinker)
```bash
php artisan tinker

# Inside tinker shell:
User::create([
    'username' => 'admin',
    'email' => 'admin@company.com',
    'password' => bcrypt('password'),
    'full_name' => 'Administrator',
    'role' => 'admin',
    'is_active' => true,
]);
```

## 🛠️ Common Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Generate application key
php artisan key:generate

# Run tests
php artisan test

# Create migration
php artisan make:migration migration_name

# Create model
php artisan make:model ModelName -m

# Create controller
php artisan make:controller ControllerName

# Tinker (interactive shell)
php artisan tinker
```

## 📊 Model Query Examples

```bash
php artisan tinker

# LAN Ports
LanPort::active()->count()              # Count active ports
LanPort::byStatus('maintenance')->get() # Get maintenance ports
LanPort::byFloor('1')->paginate(15)     # Get ports on floor 1

# Voice Ports
VoicePort::where('port_status', 'active')->count()

# Server Racks
ServerRack::all()                       # All racks
ServerRack::find(1)->rackUnits()->get() # Get all units in rack

# Audit Logs
AuditLog::recent(30)->get()            # Get logs from last 30 days
AuditLog::byAction('create')->count()   # Count create actions
AuditLog::byModel('LanPort')->get()     # Get all LAN port changes

# Users
User::byRole('technician')->get()       # Get all technicians
User::active()->count()                 # Count active users
```

## 🎨 Blade Template Syntax

```blade
<!-- Variables -->
{{ $variable }}
{!! $html !!}

<!-- Conditionals -->
@if($condition)
    Content
@else
    Other content
@endif

<!-- Loops -->
@foreach($items as $item)
    {{ $item }}
@endforeach

<!-- Authentication -->
@auth
    User is logged in
@endauth

@guest
    User is not logged in
@endguest

<!-- Include -->
@include('view-name')

<!-- Yield -->
@yield('section-name')

<!-- Forms -->
<form method="POST" action="{{ route('route-name') }}">
    @csrf
    @method('PUT')
</form>

<!-- Errors -->
@error('field-name')
    {{ $message }}
@enderror

<!-- Pagination -->
{{ $items->links() }}
```

## 🔐 Authentication

```php
// Get current user
auth()->user()
Auth::user()

// Check authentication
auth()->check()
auth()->guest()

// Check role
auth()->user()->isAdmin()
auth()->user()->isTechnician()

// Get user ID
auth()->id()

// Logout
Auth::logout()
```

## 🚨 Debugging

```bash
# View logs
tail -f storage/logs/laravel.log

# Check specific error
tail -20 storage/logs/laravel.log

# Debug in code
dd($variable)           # Dump and die
dump($variable)         # Just dump
var_dump($variable)     # PHP dump

# Enable query logging
DB::enableQueryLog();
dd(DB::getQueryLog());
```

## 📝 File Structure Reference

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── LanPortController.php
│   │   ├── VoicePortController.php
│   │   ├── ServerRackController.php
│   │   └── AuditLogController.php
│   └── Middleware/
│       └── IsAdmin.php
├── Models/
│   ├── User.php
│   ├── LanPort.php
│   ├── VoicePort.php
│   ├── ServerRack.php
│   ├── RackUnit.php
│   ├── AuditLog.php
│   └── Technician.php
└── Services/
    └── AuditLogService.php
```

## 🌐 Environment Variables

Key `.env` variables:

```env
APP_NAME=Network Infrastructure
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=network_infrastructure
DB_USERNAME=net_app
DB_PASSWORD=password

AUDIT_RETENTION_DAYS=30
AUDIT_AUTO_CLEANUP=true
```

## 📱 Form Validation Examples

```blade
<!-- Required field with error -->
<input type="text" name="wall_port_label" 
       class="form-control @error('wall_port_label') is-invalid @enderror"
       required>
@error('wall_port_label')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

<!-- Select field -->
<select name="status" class="form-select">
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
    <option value="maintenance">Maintenance</option>
</select>

<!-- Checkbox -->
<input type="checkbox" name="is_powered">

<!-- Textarea -->
<textarea name="notes" rows="3" class="form-control"></textarea>
```

## 🎯 Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created |
| 204 | No Content - Successful deletion |
| 400 | Bad Request - Invalid data |
| 401 | Unauthorized - Not authenticated |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 422 | Validation Error - Data validation failed |
| 500 | Server Error - Internal error |

## 🔍 Search/Filter Example

```blade
<form method="GET" action="{{ route('lan-ports.index') }}">
    <input type="text" name="search" value="{{ request('search') }}">
    <select name="status">
        <option value="">All</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
    </select>
    <button type="submit">Filter</button>
</form>
```

## 💾 Export Example

```php
// In controller
$headers = [
    'Content-Type' => 'text/csv; charset=utf-8',
    'Content-Disposition' => 'attachment; filename=ports.csv',
];

$callback = function () use ($ports) {
    $file = fopen('php://output', 'w');
    fputcsv($file, ['Label', 'User', 'Status']);
    foreach ($ports as $port) {
        fputcsv($file, [$port->wall_port_label, $port->user?->full_name, $port->port_status]);
    }
    fclose($file);
};

return response()->stream($callback, 200, $headers);
```

## 📋 Database Table Structure

### Users Table
```
id (PK) | username | email | password | full_name | extension_number | role | is_active | last_login | created_at | updated_at
```

### LAN Ports Table
```
id (PK) | wall_port_label | user_id (FK) | extension_number | email | switch_port | floor_user | port_status | notes | created_at | updated_at
```

### Voice Ports Table
```
id (PK) | wall_port_label | user_id (FK) | extension_number | email | pr_number | pen_number | floor_user | port_status | notes | created_at | updated_at
```

### Audit Logs Table
```
id (PK) | user_id (FK) | action | model_type | model_id | old_values (JSON) | new_values (JSON) | ip_address | user_agent | description | created_at
```

## 🔄 Common Workflows

### Creating a Port
1. Click "New LAN Port"
2. Fill in required fields
3. Submit form
4. Audit log created automatically
5. Redirect to list view

### Updating a Port
1. Click "Edit" on port
2. Modify fields
3. Submit form
4. Old/new values logged in audit
5. Redirect to list view

### Bulk Update
1. Check multiple ports
2. Select new status
3. Submit bulk action
4. Each update logged individually

### Viewing 360° Visualization
1. Go to Server Racks
2. Click "Visualize"
3. Use controls to rotate/zoom
4. Click units for details

### Exporting Data
1. Go to desired view (LAN/Voice ports)
2. (Optional) Apply filters
3. Click "Export CSV"
4. File downloads
5. Export action logged in audit

## 📞 Quick Support

**Issue**: Database connection error
- **Solution**: Check DB credentials in `.env`
```bash
php artisan tinker
DB::connection()->getPdo();
```

**Issue**: CSRF token missing
- **Solution**: Add `@csrf` to form
```blade
<form method="POST">
    @csrf
</form>
```

**Issue**: Model not found
- **Solution**: Check route model binding
```php
Route::get('/ports/{lanPort}', [LanPortController::class, 'show']);
```

**Issue**: Permission denied on storage
- **Solution**: Fix permissions
```bash
sudo chown -R www-data:www-data storage/
chmod -R 775 storage/
```

---

**For complete guides**, see:
- README.md - Project overview
- INSTALLATION.md - Setup instructions
- API_DOCUMENTATION.md - API reference
- DEPLOYMENT_VERTEX.md - Deployment guide
