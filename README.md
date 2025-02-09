# Todo Application

A modern, secure, and user-friendly todo application built with PHP and MySQL, featuring user authentication and profile management.

## Screenshot

![Screenshot 2025-02-09 164050](https://github.com/user-attachments/assets/88902a0a-5548-4994-9bad-ab080b5ce14f)
![Screenshot 2025-02-09 164129](https://github.com/user-attachments/assets/e9c18c6a-0e43-4fc7-a512-cff0aafce12a)
![Screenshot 2025-02-09 164223](https://github.com/user-attachments/assets/38a895a4-ec16-471b-9368-cf2e3a0caeca)



## Features

### User Authentication
- Secure user registration and login
- Password hashing using PHP's native password_hash()
- Session-based authentication
- Protection against SQL injection and XSS attacks

### Todo Management
- Create new todos
- Update todo status (Pending/In Progress/Completed)
- Delete todos with confirmation
- Visual indication of todo status
- User-specific todos (each user sees only their own todos)

### Profile Management
- View and update user profile
- Change email address with uniqueness validation
- Update personal bio
- Secure password change functionality
- Profile avatar generated from user's name

### Security Features
- Prepared SQL statements to prevent SQL injection
- Input validation and sanitization
- CSRF protection
- Secure session handling
- Database transaction support

## Technical Requirements

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- PDO PHP Extension
- Apache/Nginx web server

### Database Setup
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(191) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE todo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Installation

1. Clone the repository:
```bash
git clone https://github.com/1dev-hridoy/ToDo-With-PHP.git
cd todo-app
```

2. Configure your database connection:
   - Open `server/dbcon.php`
   - Update the database credentials:
```php
$host = "localhost";
$dbname = "your_database_name";
$username = "your_username";
$password = "your_password";
```

3. Import the database schema:
   - Create a new MySQL database
   - Import the SQL from `database/main.sql`

4. Configure your web server:
   - Point your web server to the project directory
   - Ensure the directory has appropriate permissions

## Project Structure

```
to-do/
├── server/
│   └── dbcon.php         # Database connection configuration
├── database/
│   └── main.sql          # Database schema
├── index.php             # Todo management interface
├── profile.php           # User profile management
├── sign-in.php           # User login
├── sign-up.php           # User registration
├── sign-out.php          # User logout
└── README.md             # Project documentation
```

## Usage

### User Registration
1. Navigate to the sign-up page
2. Enter your name, email, and password
3. Submit the form to create your account
4. You'll be automatically logged in

### Managing Todos
1. Log in to your account
2. Use the input field to add new todos
3. Change todo status using the dropdown menu
4. Delete todos using the delete button (requires confirmation)

### Profile Management
1. Click on your profile link
2. Update your personal information
3. Change your password (optional)
4. Save changes to update your profile

## Security Considerations

- All passwords are hashed using PHP's secure password_hash() function
- Database queries use prepared statements to prevent SQL injection
- User input is properly sanitized to prevent XSS attacks
- Session handling is secure and properly managed
- CSRF protection is implemented for forms
- Database operations use transactions where appropriate

## UI Features

- Dark mode interface
- Responsive design
- Interactive status updates
- Confirmation modals for destructive actions
- Success/error message feedback
- Clean and modern user interface

## Best Practices

- Separation of concerns
- Secure authentication implementation
- Input validation and sanitization
- Proper error handling
- User-friendly interface
- Responsive design
- Clean code structure

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.

## Acknowledgments

- PHP Documentation
- MySQL Documentation
- Tailwind CSS for the UI components
