# Student Result Management Application

A PHP + MySQL web application for managing and displaying student exam results.

## Features

- **Admin Panel**: Secure login, add/edit student results, manual result selection
- **Public Search**: Search results by register number, displays only available data
- **Database**: MySQL with proper validation and prepared statements

## Setup Instructions

1. **Install XAMPP**: Download and install XAMPP from https://www.apachefriends.org/

2. **Start Services**: Start Apache and MySQL from XAMPP control panel

3. **Create Database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `student_results_db`
   - Import the `db.sql` file or run the SQL commands manually

4. **Deploy Application**:
   - Copy the entire `student_results` folder to `C:\xampp\htdocs\`
   - Or create a virtual host if preferred

5. **Access Application**:
   - Public page: http://localhost/student_results/
   - Admin login: http://localhost/student_results/admin/login.php
     - Username: admin
     - Password: admin123

## File Structure

```
student_results/
├── db.sql                 # Database schema
├── config.php             # Database configuration
├── index.php              # Public search page
├── result.php             # Result display page
└── admin/
    ├── login.php          # Admin login
    ├── dashboard.php      # Admin dashboard
    ├── add.php            # Add student form
    ├── edit.php           # Edit student form
    └── logout.php         # Logout script
```

## Security Notes

- Uses prepared statements to prevent SQL injection
- Session-based authentication for admin panel
- Input validation for marks (numeric only)
- Unique register number constraint

## Technologies Used

- PHP 7+
- MySQL 5.7+
- Bootstrap 5.1.3
- HTML5/CSS3