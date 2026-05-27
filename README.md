# RealEstate CRM

A powerful Real Estate CRM built with Laravel 12 for managing leads, clients, properties, deals, and site visits.

## 🚀 Features

- **Lead Management** — Capture and track leads from multiple sources (MagicBricks, 99acres, Housing.com, etc.)
- **Client Management** — Manage buyers, sellers, tenants, and landlords
- **Property Listings** — Track properties with RERA number, price, location details
- **Deal Pipeline** — Kanban board with drag & drop stage management
- **Site Visits** — Schedule and track property visits with feedback
- **Reports & Analytics** — Revenue charts, lead source analysis, agent performance
- **Excel Export** — Export leads and deals to Excel
- **User Management** — Role-based access (Admin, Manager, Agent)
- **Activity Log** — Track all changes automatically

## 📋 Requirements

- PHP >= 8.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Composer >= 2.x
- Node.js >= 18.x (for assets)
- Laravel 12.x

## ⚡ Installation

### Step 1 — Clone / Extract
```bash
cd /your/htdocs/folder
# Extract the zip file here
cd real-estate-crm
```

### Step 2 — Install dependencies
```bash
composer install
npm install
npm run build
```

### Step 3 — Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:
```env
APP_NAME="RealEstateCRM"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=realestate_crm
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4 — Database setup
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### Step 5 — Run
```bash
php artisan serve
```

Open: `http://localhost:8000`

## 🔐 Demo Credentials

| Role    | Email              | Password |
|---------|--------------------|----------|
| Admin   | admin@demo.com     | password |
| Manager | manager@demo.com   | password |
| Agent   | agent@demo.com     | password |

## 👥 User Roles

| Feature          | Admin | Manager | Agent |
|------------------|-------|---------|-------|
| Leads            | ✅    | ✅      | ✅    |
| Clients          | ✅    | ✅      | ✅    |
| Properties       | ✅    | ✅      | ✅    |
| Deals            | ✅    | ✅      | ✅    |
| Reports          | ✅    | ✅      | ❌    |
| User Management  | ✅    | ❌      | ❌    |
| Settings         | ✅    | ❌      | ❌    |

## 🛠 Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: AdminLTE 3, Bootstrap 5, Chart.js
- **Database**: MySQL
- **Packages**: Spatie Permission, Spatie ActivityLog, FastExcel

## 📁 Folder Structure

PART B: README.md BANAO
README.md root mein banao:
markdown# RealEstate CRM

A powerful Real Estate CRM built with Laravel 12 for managing leads, clients, properties, deals, and site visits.

## 🚀 Features

- **Lead Management** — Capture and track leads from multiple sources (MagicBricks, 99acres, Housing.com, etc.)
- **Client Management** — Manage buyers, sellers, tenants, and landlords
- **Property Listings** — Track properties with RERA number, price, location details
- **Deal Pipeline** — Kanban board with drag & drop stage management
- **Site Visits** — Schedule and track property visits with feedback
- **Reports & Analytics** — Revenue charts, lead source analysis, agent performance
- **Excel Export** — Export leads and deals to Excel
- **User Management** — Role-based access (Admin, Manager, Agent)
- **Activity Log** — Track all changes automatically

## 📋 Requirements

- PHP >= 8.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Composer >= 2.x
- Node.js >= 18.x (for assets)
- Laravel 12.x

## ⚡ Installation

### Step 1 — Clone / Extract
```bash
cd /your/htdocs/folder
# Extract the zip file here
cd real-estate-crm
```

### Step 2 — Install dependencies
```bash
composer install
npm install
npm run build
```

### Step 3 — Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:
```env
APP_NAME="RealEstate CRM"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=realestate_crm
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4 — Database setup
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### Step 5 — Run
```bash
php artisan serve
```

Open: `http://localhost:8000`

## 🔐 Demo Credentials

| Role    | Email              | Password |
|---------|--------------------|----------|
| Admin   | admin@demo.com     | password |
| Manager | manager@demo.com   | password |
| Agent   | agent@demo.com     | password |

## 👥 User Roles

| Feature          | Admin | Manager | Agent |
|------------------|-------|---------|-------|
| Leads            | ✅    | ✅      | ✅    |
| Clients          | ✅    | ✅      | ✅    |
| Properties       | ✅    | ✅      | ✅    |
| Deals            | ✅    | ✅      | ✅    |
| Reports          | ✅    | ✅      | ❌    |
| User Management  | ✅    | ❌      | ❌    |
| Settings         | ✅    | ❌      | ❌    |

## 🛠 Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: AdminLTE 3, Bootstrap 5, Chart.js
- **Database**: MySQL
- **Packages**: Spatie Permission, Spatie ActivityLog, FastExcel

## 📁 Folder Structure
app/
├── Http/Controllers/CRM/    # All CRM controllers
├── Models/                  # Eloquent models
├── Services/                # Business logic
├── Exports/                 # Excel exports
└── Policies/                # Authorization policies
resources/views/crm/         # All Blade views
routes/crm.php               # CRM routes
config/crm.php               # CRM configuration

## ⚙️ Configuration

All CRM settings are in `config/crm.php`:
- Pipeline stages
- Property types
- Lead sources
- Currencies

## 📝 Changelog

### v1.0.0 (2025)
- Initial release
- Lead, Client, Property, Deal management
- Kanban pipeline board
- Site visit scheduling
- Reports & Excel export
- Role-based access control

