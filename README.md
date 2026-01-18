# School Management System (SMS)

A pure PHP MVC web application for managing a primary school system.

## Project Structure

*   `school_app/`: Main application source code.
*   `schema.sql`: Database schema definition.
*   `assets/`: Project diagrams and design resources.

## Design Resources

*   **Figma Designs**: [CSE3201 - Internet Computing Designs](https://www.figma.com/design/55xg9z9nf7Eaud3lRy7lAP/CSE3201---Internet-Computing-Designs?node-id=61-14493&t=OcmeCRF6WIK604D0-1)

## Setup Instructions

1.  **Environment**: Ensure XAMPP is installed and running (Apache + MySQL).
2.  **Database Configuration**:
    *   The app mimics a real-world "installer" pattern.
    *   Open your browser and navigate to the project directory:
        `http://localhost/path/to/school_app/install.php`
    *   This script will create the database `school_app` and import all tables from `schema.sql`.
3.  **Run Application**:
    *   After installation, delete `install.php` (optional, for security).
    *   Navigate to: `http://localhost/path/to/school_app/`

## Default Credentials (Seed Data)
(If you have seeded users in `schema.sql` or created them)
*   User: ...
*   Password: ...
