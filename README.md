# Angaza Referral System – Comprehensive Developer & Admin Guide

---

## 1. Introduction
A web-based application for managing patient referrals, integrating with eCHIS, HIE, and supporting FHIR standards. Features include referral journey visualization, SMS notifications, audit trails, analytics, and robust API documentation.

---

## 2. Setup & Installation

### 2.1 Developer Prerequisites
- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Node.js 18 or higher
- npm or yarn
- Git
- Python 3.x (for documentation)
- pip (Python package manager)

### 2.2 User Prerequisites
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Internet connection (2 Mbps+ recommended)
- Desktop/Laptop: Windows 10/11, macOS 10.15+, or Linux
- Mobile: iOS 13+ or Android 9+
- Tablet: iPadOS 13+ or Android 9+

### 2.3 Installation Steps (Developer)

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/angaza-referral.git
   cd angaza-referral/system
   ```
2. **Install PHP dependencies:**
   ```bash
   composer install
   ```
3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```
4. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   - Edit `.env` and set your database credentials and all required API keys/URLs.
5. **Configure database:**
   - Create a MySQL database
   - Update `.env` with database credentials
   - Run migrations:
     ```bash
     php artisan migrate
     ```
   - Seed the database (optional or as needed):
     ```bash
     php artisan db:seed
     ```
6. **Start development server:**
   ```bash
   php artisan serve
   ```

### 2.4 Installation Steps (User/First-Time Login)
1. Access the system through your web browser
2. Log in using your credentials (or create an account if enabled)
3. Complete profile setup and security steps (2FA, password, etc.)
4. Navigate to dashboard and begin using the system

### 2.5 Account Creation & Profile Setup
- Visit the system URL provided by your administrator
- Click "Create Account" and enter required information (name, email, phone, organization)
- Create a strong password and verify your email
- Complete profile setup (picture, contact info, notification preferences, language)

### 2.6 Database Migration & Seeding
- **Run all pending migrations:**
  ```bash
  php artisan migrate
  ```
- **Rollback last migration:**
  ```bash
  php artisan migrate:rollback
  ```
- **Refresh all migrations:**
  ```bash
  php artisan migrate:refresh
  ```
- **Seed data:**
  ```bash
  php artisan db:seed
  ```
- **Custom seeder example:**
  ```php
  class UserSeeder extends Seeder {
      public function run() {
          User::create([
              'username' => 'admin',
              'email' => 'admin@example.com',
              'password' => Hash::make('password')
          ]);
      }
  }
  ```
- **Migration best practices:**
  - Always backup database before migrations
  - Test migrations in development first
  - Include rollback logic
  - Use descriptive migration names
  - Maintain referential integrity

### 2.7 Common Issues & Troubleshooting
- **Database Connection:**
  - Verify MySQL is running
  - Check database credentials
  - Ensure database exists
- **Composer Issues:**
  - Clear composer cache
  - Update composer
  - Check PHP version
- **Node.js Issues:**
  - Clear npm cache
  - Delete `node_modules` and reinstall
- **Migration Issues:**
  - Check migration logs
  - Verify database connection and permissions
  - Review error messages
  - Test rollback procedures
- **Login Problems:**
  - Password reset
  - Account lockout
  - Browser or network issues
- **Access Issues:**
  - Permission errors
  - Role assignment
  - Feature availability
- **Data Entry:**
  - Form validation
  - Required fields
  - Data format
  - Save errors

### 2.8 Project Structure & Configuration (for Developers)
- `system/` – Main Laravel application
- `docs/` – Documentation source files
- `database/migrations/` – Migration files
- `database/seeders/` – Seeder files
- `public/` – Public assets and entry point
- `resources/views/` – Blade templates
- `routes/` – Route definitions
- `app/` – Application logic (controllers, models, services)

### 2.9 Documentation Setup (for Contributors)
- **Create and activate a virtual environment:**
  ```bash
  python -m venv .venv
  source .venv/bin/activate
  ```
- **Install documentation tools:**
  ```bash
  pip install mkdocs-material mkdocs-git-revision-date-localized-plugin mkdocs-minify-plugin
  ```
- **Run documentation server:**
  ```bash
  mkdocs serve
  ```
- **Build documentation for production:**
  ```bash
  mkdocs build
  ```

---

## 3. Environment Variables

### eCHIS Integration
```
ECHIS_API_URL=https://your-echis-api-url
ECHIS_API_USERNAME=your_echis_username
ECHIS_API_PASSWORD=your_echis_password
```

### HIE Integration
```
HIE_API_URL=https://your-hie-api-url
HIE_API_KEY=your_hie_api_key
```

### SMS/CHP Notification
```
SMS_API_KEY=your_africastalking_api_key
SMS_USERNAME=your_africastalking_username
SMS_FROM=Angaza
```

---

## 4. Artisan Commands

### eCHIS Integration
- Inbound sync (fetch from eCHIS):
  ```bash
  php artisan echis:sync-inbound
  ```
- Outbound sync (push to eCHIS):
  ```bash
  php artisan echis:sync-outbound
  ```

### HIE Integration
- Facility/service sync:
  ```bash
  php artisan hie:sync-facilities
  ```

### Reporting/Analytics
- Run feature tests:
  ```bash
  php artisan test --filter=FhirApiTest
  ```

---

## 5. Scheduling & Automation
- Add to `app/Console/Kernel.php`:
  ```php
  $schedule->command('echis:sync-inbound')->everyFifteenMinutes();
  $schedule->command('echis:sync-outbound')->everyFifteenMinutes();
  $schedule->command('hie:sync-facilities')->dailyAt('01:00');
  ```
- Add to crontab:
  ```bash
  * * * * * cd /path/to/angaza_referral3/system && php artisan schedule:run >> /dev/null 2>&1
  ```

---

## 6. Core Features

### Patient Journey & Referral Tree
- Visualizes all referral transitions (escalation, down-referral, re-referral)
- Clickable transitions open a modal with full referral details
- Status, CHP assignment, and notification tracking
- Audit trail of all status changes, visible in the UI

### CHP Notification (SMS)
- SMS sent to assigned CHP on down-referral or completion
- Manual re-send button in referral details modal
- Notification status tracked (`chp_notified`)
- Configure SMS provider in `.env`

### Reporting & Analytics
- Access via `/reports/referral-volume`
- Filter by status, facility, CHP, date range
- Export to CSV
- Interactive charts (Chart.js) for status breakdown and time series

### HIE Fallback Logic
- If HIE is unavailable, system falls back to local facility/service data
- Logs fallback events for admin review

### Security & Compliance
- All credentials in `.env` (never commit secrets)
- Role-based access control for sensitive actions
- All status changes and notifications logged (audit trail)

---

## 7. Testing & Troubleshooting

### Test Commands
- Run all tests:
  ```bash
  php artisan test
  ```
- Run feature tests only:
  ```bash
  php artisan test --testsuite=Feature
  ```
- Run unit tests only:
  ```bash
  php artisan test --testsuite=Unit
  ```
- Run a specific test class:
  ```bash
  php artisan test --filter=FhirFormatTest
  ```
- Run integration tests (if available):
  ```bash
  php artisan test --filter=IntegrationCommandTest
  ```
- Inspect data with tinker:
  ```bash
  php artisan tinker
  >>> App\Models\Patient::all();
  >>> App\Models\Referral::all();
  ```
- Count patients and referrals:
  ```bash
  php artisan tinker --execute="echo 'Patients: ' . \\App\\Models\\Patient::count() . ', Referrals: ' . \\App\\Models\\Referral::count();"
  ```
- List patient IDs and UPIs:
  ```bash
  php artisan tinker --execute="foreach(\\App\\Models\\Patient::all(['id','upi']) as $p) { echo $p->id . ':' . $p->upi . PHP_EOL; }"
  ```
- If migrations or seeders fail:
  ```bash
  php artisan migrate:fresh --seed
  ```
- Check logs for sync, notification, or fallback errors

---

## 8. Quick Reference: Common Commands

```bash
# eCHIS
php artisan echis:sync-inbound
php artisan echis:sync-outbound

# HIE
php artisan hie:sync-facilities

# Reporting
php artisan test --filter=FhirApiTest

# Testing
php artisan test
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
php artisan test --filter=FhirFormatTest

# Migrate/seed
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Scheduler (run all scheduled tasks)
php artisan schedule:run
```

---

## 9. Best Practices
- Test all integrations in a sandbox before production
- Monitor logs and dashboards regularly
- Use strong, unique API keys and rotate them periodically
- Regularly back up your database and configuration
- Review and update user/admin permissions as needed

---

## 10. Documentation & Support
- Keep this README and user/admin guides up to date
- Document all new features, commands, and workflows
- For help, contact the project maintainer or open an issue

---

# Extended Reference & Diagrams

## Flowchart

[![Patient Referral System Flowchart](https://camo.githubusercontent.com/4c6e7df670a663d3dfc14946ff20ba845cc0a423ebd4ed55d5dc4b8ac6e88ce0/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f466c6f7725323063686172742e706e67)](https://camo.githubusercontent.com/4c6e7df670a663d3dfc14946ff20ba845cc0a423ebd4ed55d5dc4b8ac6e88ce0/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f466c6f7725323063686172742e706e67)

The flowchart shows the high-level process flow for the patient referral system. The process begins with client registration, and continues with the capture of medical information and clinical summary. A referral is then created, and the referral status is tracked until feedback is received.

## Use Case Diagram

[![Patient Referral System Use Case Diagram](https://camo.githubusercontent.com/b58326dc63821ceb16a80f70761f3ab38117cbf94d61f1dad47ae22aa1bbbac7/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f526566657272616c25323076657273696f6e253230322d5573652d636173652532306469616772616d2e64726177696f2532302832292e706e67)](https://camo.githubusercontent.com/b58326dc63821ceb16a80f70761f3ab38117cbf94d61f1dad47ae22aa1bbbac7/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f526566657272616c25323076657273696f6e253230322d5573652d636173652532306469616772616d2e64726177696f2532302832292e706e67)

The use case diagram shows the different actors that interact with the patient referral system and the use cases that they can perform. The actors include the client, referring health worker, referral coordinator, receiving facility and shared health record

## Activity Diagram

[![Patient Referral System Flowchart](https://camo.githubusercontent.com/5387418fb78d8aa7ce48b20fe7f7462d3770526ef4fc640a4560b787e138370f/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f526566657272616c25323076657273696f6e253230322d506167652d362e64726177696f2e706e67)](https://camo.githubusercontent.com/5387418fb78d8aa7ce48b20fe7f7462d3770526ef4fc640a4560b787e138370f/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f526566657272616c25323076657273696f6e253230322d506167652d362e64726177696f2e706e67)

The flowchart shows the high-level process flow for the patient referral system. The process begins with client registration, and continues with the capture of medical information and clinical summary. A referral is then created, and the referral status is tracked until feedback is received.

## Data Flow Diagram

[![Patient Referral System Data Flow Diagram](https://camo.githubusercontent.com/362bcd78c11ced03fe1282a136ceed85eda779c8af27ff941a28ce76115a567c/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f5768617473417070253230496d616765253230323032332d30342d31342532306174253230322e35302e3136253230504d2e6a706567)](https://camo.githubusercontent.com/362bcd78c11ced03fe1282a136ceed85eda779c8af27ff941a28ce76115a567c/68747470733a2f2f696d6167657374756d2e626c6f622e636f72652e77696e646f77732e6e65742f6469616772616d732f5768617473417070253230496d616765253230323032332d30342d31342532306174253230322e35302e3136253230504d2e6a706567)

The data flow diagram shows the flow of information in the patient referral system. The system captures information about clients, medical information, referrals, and feedback, and stores this information in a database. The information is then used to generate reports and provide feedback to the referring health worker.

The activity diagram shows the detailed process flow for creating a referral in the patient referral system. The process begins with the creation of a new referral, and continues with the selection of the referral priority, entry of the diagnosis and reason for referral, selection of the physician/provider, and submission of the referral. The process concludes with the tracking of the referral status until feedback is received.

---

