# Network Infrastructure Management API Documentation

## Base URL
```
https://your-vertex-server/api
```

## Authentication
All endpoints require local credential-based authentication. Include credentials in request headers:
- Username: `username`
- Password: `password` (hashed)

## Error Responses

### Standard Error Response
```json
{
    "message": "Error description",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden (Insufficient permissions)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## LAN Ports Endpoints

### List LAN Ports
```
GET /lan-ports
```

**Query Parameters:**
- `status` (optional): `active`, `inactive`, `maintenance`
- `floor` (optional): `1`, `2`, `3`, `4`
- `search` (optional): Search by wall_port_label, email, or switch_port
- `page` (optional): Page number for pagination

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "wall_port_label": "A1-001",
            "user_id": 10,
            "extension_number": "5001",
            "email": "user@company.com",
            "switch_port": "S1-Gi0/1/48",
            "floor_user": "1",
            "port_status": "active",
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-15T10:30:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 200
    }
}
```

### Create LAN Port
```
POST /lan-ports
```

**Request Body:**
```json
{
    "wall_port_label": "A1-002",
    "user_id": 10,
    "extension_number": "5002",
    "email": "user2@company.com",
    "switch_port": "S1-Gi0/1/47",
    "floor_user": "1",
    "port_status": "active",
    "notes": "Optional notes"
}
```

**Response:** `201 Created`
```json
{
    "id": 2,
    "wall_port_label": "A1-002",
    "user_id": 10,
    "extension_number": "5002",
    "email": "user2@company.com",
    "switch_port": "S1-Gi0/1/47",
    "floor_user": "1",
    "port_status": "active",
    "created_at": "2024-01-15T10:35:00Z"
}
```

### Get LAN Port
```
GET /lan-ports/{id}
```

**Response:** `200 OK`
```json
{
    "id": 1,
    "wall_port_label": "A1-001",
    "user_id": 10,
    "extension_number": "5001",
    "email": "user@company.com",
    "switch_port": "S1-Gi0/1/48",
    "floor_user": "1",
    "port_status": "active",
    "user": {
        "id": 10,
        "full_name": "John Doe",
        "username": "jdoe",
        "email": "john@company.com"
    },
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
}
```

### Update LAN Port
```
PUT /lan-ports/{id}
```

**Request Body:** Same as Create (all fields optional for updates)

**Response:** `200 OK` - Updated resource

### Delete LAN Port
```
DELETE /lan-ports/{id}
```

**Response:** `204 No Content`

### Bulk Update Port Status
```
POST /lan-ports/bulk/status
```

**Request Body:**
```json
{
    "port_ids": [1, 2, 3, 4, 5],
    "status": "active"
}
```

**Response:** `200 OK`
```json
{
    "success": true,
    "message": "Ports updated successfully",
    "updated_count": 5
}
```

### Export LAN Ports
```
GET /lan-ports/export/csv
```

**Response:** CSV file download

---

## Voice Ports Endpoints

### List Voice Ports
```
GET /voice-ports
```

**Query Parameters:** Same as LAN ports

**Response:** Similar structure to LAN ports with additional fields:
```json
{
    "data": [
        {
            "id": 1,
            "wall_port_label": "V1-001",
            "user_id": 10,
            "extension_number": "5001",
            "email": "user@company.com",
            "pr_number": "PR-12345",
            "pen_number": "PEN-67890",
            "floor_user": "1",
            "port_status": "active",
            "created_at": "2024-01-15T10:30:00Z"
        }
    ]
}
```

### Create Voice Port
```
POST /voice-ports
```

**Request Body:**
```json
{
    "wall_port_label": "V1-002",
    "user_id": 10,
    "extension_number": "5002",
    "email": "user2@company.com",
    "pr_number": "PR-12346",
    "pen_number": "PEN-67891",
    "floor_user": "1",
    "port_status": "active",
    "notes": "Optional notes"
}
```

**Validation:**
- `pr_number`: Must match format `PR-XXXXX` or custom format
- `pen_number`: Must match format `PEN-XXXXX` or custom format
- Both must be unique

### Get Voice Port
```
GET /voice-ports/{id}
```

### Update Voice Port
```
PUT /voice-ports/{id}
```

### Delete Voice Port
```
DELETE /voice-ports/{id}
```

### Bulk Update Voice Port Status
```
POST /voice-ports/bulk/status
```

### Export Voice Ports
```
GET /voice-ports/export/csv
```

---

## Server Racks Endpoints

### List Server Racks
```
GET /server-racks
```

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "rack_name": "Rack-A1",
            "location": "Data Center A",
            "total_units": 42,
            "rack_type": "standard",
            "occupied_count": 28,
            "utilization_percentage": 66.67,
            "created_at": "2024-01-15T10:30:00Z"
        }
    ]
}
```

### Create Server Rack
```
POST /server-racks
```

**Request Body:**
```json
{
    "rack_name": "Rack-B2",
    "location": "Data Center B",
    "total_units": 42,
    "rack_type": "standard",
    "description": "Optional description"
}
```

### Get Server Rack
```
GET /server-racks/{id}
```

### Get Server Rack Visualization Data
```
GET /server-racks/{id}/api/visualization
```

**Response:**
```json
{
    "id": 1,
    "name": "Rack-A1",
    "location": "Data Center A",
    "total_units": 42,
    "utilization": 66.67,
    "units": [
        {
            "id": 1,
            "unit_number": 42,
            "equipment_name": "Core Switch",
            "equipment_type": "switch",
            "is_powered": true
        }
    ]
}
```

### Get 360° Visualization
```
GET /server-racks/{id}/visualize
```

**Response:** HTML page with 3D visualization

### Add Equipment to Rack
```
POST /server-racks/{id}/equipment
```

**Request Body:**
```json
{
    "unit_number": 42,
    "unit_height": 2,
    "equipment_name": "Dell PowerEdge R750",
    "equipment_type": "server",
    "is_powered": true,
    "power_consumption": "750"
}
```

**Equipment Types:**
- `server`
- `switch`
- `router`
- `firewall`
- `pdu`
- `patch_panel`
- `console_server`
- `cable_manager`

### Update Rack Unit
```
PUT /server-racks/unit/{unitId}
```

**Request Body:**
```json
{
    "equipment_name": "Updated Name",
    "description": "Updated description",
    "is_powered": true,
    "power_consumption": "800"
}
```

### Remove Equipment from Rack Unit
```
DELETE /server-racks/unit/{unitId}
```

---

## Audit Logs Endpoints

**Note:** Audit logs endpoints are restricted to IT staff (admin role)

### List Audit Logs
```
GET /audit-logs
```

**Query Parameters:**
- `action` (optional): `create`, `update`, `delete`, `export`, `view`
- `model_type` (optional): `LanPort`, `VoicePort`, `ServerRack`, `RackUnit`
- `user_id` (optional): Filter by user
- `date_from` (optional): Format `YYYY-MM-DD`
- `date_to` (optional): Format `YYYY-MM-DD`
- `page` (optional): Page number

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "user_id": 5,
            "action": "create",
            "model_type": "LanPort",
            "model_id": 1,
            "old_values": null,
            "new_values": {
                "wall_port_label": "A1-001",
                "port_status": "active"
            },
            "ip_address": "192.168.1.100",
            "user_agent": "Mozilla/5.0...",
            "description": "Port created",
            "created_at": "2024-01-15T10:30:00Z",
            "user": {
                "id": 5,
                "full_name": "Admin User",
                "username": "admin"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 25,
        "total": 1500
    }
}
```

### Get Audit Log Details
```
GET /audit-logs/{id}
```

### Export Audit Logs
```
GET /audit-logs/export/csv
```

**Query Parameters:** Same as List

**Response:** CSV file download

### Get Statistics
```
GET /audit-logs/statistics/view
```

**Query Parameters:**
- `days` (optional): Number of days to analyze (default 30)

**Response:**
```json
{
    "total_actions": 1500,
    "by_action": {
        "create": 500,
        "update": 800,
        "delete": 100,
        "export": 50,
        "view": 50
    },
    "by_model": {
        "LanPort": 600,
        "VoicePort": 400,
        "ServerRack": 200,
        "RackUnit": 300
    },
    "by_user": [
        {
            "user": "Admin User",
            "count": 750
        }
    ]
}
```

### Purge Old Logs
```
POST /audit-logs/purge
```

**Response:** `200 OK`
```json
{
    "success": true,
    "message": "Purged 500 audit log entries"
}
```

---

## Dashboard Endpoints

### Get Dashboard Statistics
```
GET /api/dashboard/stats
```

**Response:**
```json
{
    "lan_ports": {
        "total": 200,
        "active": 180,
        "inactive": 15,
        "maintenance": 5
    },
    "voice_ports": {
        "total": 150,
        "active": 140,
        "inactive": 8,
        "maintenance": 2
    },
    "server_racks": 4
}
```

---

## Rate Limiting

- Standard endpoints: 100 requests per minute per user
- Export endpoints: 10 requests per minute per user
- Audit log access: 50 requests per minute per admin

---

## Data Retention

- Audit logs are retained for **30 days** and automatically purged
- Export audit logs regularly for compliance archival
- All deletion operations are logged and cannot be undone

---

## Examples

### cURL - Create LAN Port
```bash
curl -X POST https://server/lan-ports \
  -H "Authorization: Bearer token" \
  -H "Content-Type: application/json" \
  -d '{
    "wall_port_label": "A1-001",
    "switch_port": "S1-Gi0/1/48",
    "floor_user": "1",
    "port_status": "active"
  }'
```

### cURL - Export Ports
```bash
curl -X GET https://server/lan-ports/export/csv \
  -H "Authorization: Bearer token" \
  -o lan_ports.csv
```

### Python - List Ports
```python
import requests

response = requests.get(
    'https://server/lan-ports',
    params={'status': 'active', 'floor': '1'},
    auth=('username', 'password')
)

ports = response.json()
print(ports)
```

---

## Support & Troubleshooting

### Common Errors

**401 Unauthorized**
- Verify credentials are correct
- Check if account is active
- Ensure session hasn't expired

**403 Forbidden**
- User lacks required permissions
- Admin role required for certain endpoints
- Check user role and access level

**422 Validation Error**
- Check required fields are provided
- Verify data format (email, numbers, etc.)
- Ensure uniqueness constraints are met

---

## Changelog

### v1.0.0 (2024-01-15)
- Initial API release
- LAN and Voice port management
- Server rack visualization
- Audit logging system
- Dashboard statistics
