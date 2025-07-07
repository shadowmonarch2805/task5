Task 4 Security Features Documentation

Objective: Secure the application against common web vulnerabilities and implement role-based access control.

Features Included:
1. Prepared Statements: All database queries use MySQLi prepared statements to prevent SQL injection.
2. Server-Side Validation: All forms are validated using PHP before processing data.
3. Client-Side Validation: HTML5 attributes and Bootstrap styles are used to validate forms before submission.
4. User Roles: Two roles implemented - 'admin' and 'editor'. Admins can edit/delete any post. Editors can create/view only.
5. Role-Based Access Control: Admin-only functions like delete/edit are restricted in UI and backend.

SQL to Add Role Column:
ALTER TABLE users ADD COLUMN role ENUM('admin', 'editor') DEFAULT 'editor';

Setup Instructions:
1. Extract to C:/xampp/htdocs/blog-app-secure
2. Start XAMPP (Apache + MySQL)
3. Go to http://localhost/phpmyadmin
4. Create a database: blog
5. Run the SQL in this file to create users and posts tables (including 'role')
6. Visit http://localhost/blog-app-secure/register.php to begin.
