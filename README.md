# Base Laravel API with Authentication, Roles, and Permissions

---

## Overview

This project provides a foundational Laravel API implementation, featuring built-in authentication, role-based access control, and permission management. Ideal for projects requiring secure access and user management features.

---

## How to Start the Project

1. **Ensure Required Tools**:
   - PHP (>= 8.3)
   - Composer
   - A relational database (e.g., MySQL, PostgreSQL, or SQLite)

2. **Clone the Repository**:
   ```bash
   git clone https://github.com/KauanCalheiro/auth-api.git
   ```

3. **Navigate to the Project Directory**:
   ```bash
   cd auth-api
   ```

4. **Install Dependencies**:
   ```bash
   composer install
   ```

5. **Configure Environment**:
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Edit the `.env` file to configure your database credentials and other environment variables as needed.

6. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

7. **Run Migrations**:
   - Set up the database tables:
     ```bash
     php artisan migrate
     ```

8. **Seed Initial Data** (Optional):
   - Populate the database with default roles and permissions (if included):
     ```bash
     php artisan db:seed
     ```

9. **Start the Development Server**:
   ```bash
   php artisan serve
   ```

10. **Access the Application**:
    - Open your browser and navigate to: `http://127.0.0.1:8000`

---

## Features

- **Authentication**:
  - User registration and login.
  - Password hashing and reset functionality.
- **Roles and Permissions**:
  - Predefined roles (e.g., Admin, User) with associated permissions.
  - Middleware to secure routes based on roles and permissions.
- **API Documentation**:
  - Exportable Postman collection for testing endpoints.

---

## Importing the Collection into Postman

1. **Install Postman**:
   - If not already installed, download [Postman](https://www.postman.com/).

2. **Locate the Collection File**:
   - Find the `postman-requests.json` file in the project root directory.

3. **Import the Collection**:
   - Open Postman.
   - Click on **Import** in the main menu.
   - Select the `postman-requests.json` file and click **Import**.

4. **Set Up Environment Variables**:
   - Configure the Postman environment variables (e.g., `{{URL}}`) to match your application’s base URL.

5. **Test Endpoints**:
   - Use the imported collection to test the API endpoints.

---

## Notes and Best Practices

- **Environment Variables**:
  - Ensure the `.env` file is correctly configured for your local environment and does not contain sensitive information when shared.

- **Database**:
  - Backup your database before running migrations or seeders.

- **API Base URL**:
  - Replace `{{URL}}` in Postman with your application's base URL.

- **Code Customization**:
  - Extend the existing roles and permissions as per your application's requirements.