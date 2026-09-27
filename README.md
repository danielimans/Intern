# Network Infrastructure Documentation System

A comprehensive Laravel-based system for managing network infrastructure, tracking LAN/Voice ports, server racks, and audit logs.

## Features

- **LAN Port Management**: Track physical port assignments, user details, switch ports, and floor information
- **Voice Port Management**: Manage voice ports with PR numbers, PEN numbers, and extension tracking
- **Port Status Tracking**: Active, inactive, and maintenance status management
- **360° Server Rack Visualization**: Interactive 3D visualization of server racks with zoom and details
- **Audit Logging**: Complete audit trail of all changes (30-day retention, IT staff access only)
- **Technician Support**: Track which technician assisted with support tasks
- **User Management**: Support for 200+ users with role-based access control

## Technology Stack

- **Backend**: PHP 8.x with Laravel Framework
- **Frontend**: Blade Templating Engine
- **Database**: MySQL 8.x
- **Deployment**: Vertex
- **Authentication**: Local Credentials

## Project Structure

```
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── LanPort.php
│   │   ├── VoicePort.php
│   │   ├── ServerRack.php
│   │   ├── RackUnit.php
│   │   ├── AuditLog.php
│   │   └── Technician.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── LanPortController.php
│   │   │   ├── VoicePortController.php
│   │   │   ├── ServerRackController.php
│   │   │   ├── AuditLogController.php
│   │   │   └── AuthController.php
│   │   └── Requests/
│   │       ├── StoreLanPortRequest.php
│   │       └── StoreVoicePortRequest.php
│   └── Services/
│       ├── AuditLogService.php
│       └── RackVisualizationService.php
├── database/
│   └── migrations/
│       ├── 2024_01_01_000001_create_users_table.php
│       ├── 2024_01_01_000002_create_lan_ports_table.php
│       ├── 2024_01_01_000003_create_voice_ports_table.php
│       ├── 2024_01_01_000004_create_server_racks_table.php
│       ├── 2024_01_01_000005_create_rack_units_table.php
│       ├── 2024_01_01_000006_create_audit_logs_table.php
│       └── 2024_01_01_000007_create_technicians_table.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── lan-ports/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── voice-ports/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── server-racks/
│   │   │   └── visualize.blade.php
│   │   └── audit-logs/
│   │       └── index.blade.php
│   └── css/
│       └── app.css
├── routes/
│   └── web.php
└── config/
    └── audit.php
```

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env`
4. Generate application key: `php artisan key:generate`
5. Configure database in `.env`
6. Run migrations: `php artisan migrate`
7. Seed default data: `php artisan db:seed`
8. Run the application: `php artisan serve`

## Database Schema

### Users Table
- id (Primary Key)
- username (Unique)
- email (Unique)
- password (Hashed)
- full_name
- extension_number
- role (technician)
- is_active
- created_at, updated_at

### LAN Ports Table
- id (Primary Key)
- wall_port_label (Unique)
- user_id (Foreign Key)
- extension_number
- email
- switch_port
- floor_user
- port_status (active, inactive, maintenance)
- created_at, updated_at

### Voice Ports Table
- id (Primary Key)
- wall_port_label (Unique)
- user_id (Foreign Key)
- extension_number
- email
- pr_number
- pen_number
- floor_user
- port_status (active, inactive, maintenance)
- created_at, updated_at

### Server Racks Table
- id (Primary Key)
- rack_name
- location
- total_units
- rack_type
- created_at, updated_at

### Rack Units Table
- id (Primary Key)
- server_rack_id (Foreign Key)
- unit_number
- equipment_name
- equipment_type
- port_connections (JSON)
- created_at, updated_at

### Audit Logs Table
- id (Primary Key)
- user_id (Foreign Key)
- action (create, update, delete, export, view)
- model_type (LanPort, VoicePort, ServerRack, etc.)
- model_id
- old_values (JSON)
- new_values (JSON)
- ip_address
- user_agent
- created_at (with 30-day retention policy)

### Technicians Table
- id (Primary Key)
- user_id (Foreign Key)
- support_ticket_id
- assisted_port_id
- assisted_port_type (lan, voice)
- assistance_notes
- created_at, updated_at

## API Endpoints

### LAN Port Management
- `GET /lan-ports` - List all LAN ports
- `GET /lan-ports/{id}` - View specific LAN port
- `POST /lan-ports` - Create new LAN port
- `PUT /lan-ports/{id}` - Update LAN port
- `DELETE /lan-ports/{id}` - Delete LAN port

### Voice Port Management
- `GET /voice-ports` - List all voice ports
- `GET /voice-ports/{id}` - View specific voice port
- `POST /voice-ports` - Create new voice port
- `PUT /voice-ports/{id}` - Update voice port
- `DELETE /voice-ports/{id}` - Delete voice port

### Server Rack Visualization
- `GET /server-racks` - List all server racks
- `GET /server-racks/{id}/visualize` - Get 360° visualization data
- `GET /server-racks/{id}/units` - Get rack unit details

### Audit Logs (IT Staff Only)
- `GET /audit-logs` - List all audit logs
- `GET /audit-logs/export` - Export audit logs

## Key Features Implementation

### 1. Port Status Management
Ports can be in three states:
- **Active**: In use and operational
- **Inactive**: Not currently in use
- **Maintenance**: Under maintenance or temporarily disabled

### 2. Audit Logging
All changes are automatically logged with:
- User who made the change
- Timestamp of change
- Old and new values
- IP address and user agent
- Action type (create, update, delete)

### 3. 360° Server Rack Visualization
Interactive visualization with:
- Rotatable 3D view of server rack
- Clickable rack units to view equipment details
- Zoom in/out functionality
- Port connection details

### 4. Role-Based Access Control
- **Technician Role**: Can create, read, update LAN/Voice ports and view audit logs

### 5. Data Retention
- Audit logs are automatically purged after 30 days
- Manual archival option available for compliance

## Security Features

- Local credential-based authentication
- Password hashing (bcrypt)
- CSRF protection
- SQL injection prevention via parameterized queries
- Rate limiting on sensitive endpoints
- Audit trail for compliance

## Performance Optimization

- Database indexing on frequently queried fields
- Pagination for large datasets
- Caching of server rack visualization data
- Lazy loading of port details

## Configuration

### Audit Configuration (`config/audit.php`)
```php
return [
    'retention_days' => 30,
    'excluded_models' => [],
    'audited_events' => ['created', 'updated', 'deleted'],
    'it_staff_only' => true,
];
```

## Development Guidelines

1. All database changes must go through migrations
2. Models should include appropriate relationships and scopes
3. Controllers should follow REST principles
4. Blade templates should utilize Laravel's templating features
5. Audit logging should be automatic via model observers

## Testing

Run tests with: `php artisan test`

## Support

For issues or questions, contact the infrastructure team.
