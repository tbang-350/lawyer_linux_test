# Lawyer Calendar Management System

A modern web application for lawyers to efficiently manage and track their court appointments. Built with Laravel and featuring a clean, professional interface.

## Features

- **Authentication & User Roles**
  - Secure login system
  - Role-based access control (Admin/Lawyer)
  - User profile management

- **Calendar Interface**
  - Interactive calendar view
  - Appointment creation and management
  - Multiple view options (Month/Week/Day)

- **Appointment Management**
  - Create, edit, and delete appointments
  - Assign multiple lawyers to appointments
  - Track case details and court locations

- **Email Notifications**
  - Customizable reminder settings
  - Automated email notifications
  - Daily digest options

- **Dashboard**
  - Overview of upcoming appointments
  - Statistics and key metrics
  - Quick access to important information

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM
- Redis (for queue processing)

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd lawyer-calendar
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Create environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lawyer_calendar
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

7. Configure mail settings in `.env`:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=your_smtp_host
   MAIL_PORT=587
   MAIL_USERNAME=your_username
   MAIL_PASSWORD=your_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email@example.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

8. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

9. Build assets:
   ```bash
   npm run build
   ```

10. Start the development server:
    ```bash
    php artisan serve
    ```

## Usage

1. Access the application at `http://localhost:8000`
2. Log in with the default admin credentials:
   - Email: admin@example.com
   - Password: password

3. Create a new firm and add lawyers through the admin interface
4. Start creating and managing court appointments

## Scheduling Reminders

The system includes a command to send appointment reminders. To ensure reminders are sent:

1. Set up a cron job to run Laravel's scheduler:
   ```bash
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

2. Configure your queue worker:
   ```bash
   php artisan queue:work
   ```

## Development

- Run tests:
  ```bash
  php artisan test
  ```

- Watch for asset changes:
  ```bash
  npm run dev
  ```

## Security

- All passwords are hashed using Laravel's secure hashing algorithm
- CSRF protection is enabled
- Input validation is implemented for all forms
- Role-based access control is enforced

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
