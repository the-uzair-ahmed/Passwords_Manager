<img width="801" height="621" alt="image" src="https://github.com/user-attachments/assets/7d9c5ffa-2735-409a-b977-636dcf9e3a83" />

# 🔐 SparxByte Password Manager

A modern and secure **Password Manager Web Application** built with **PHP, MySQL, JavaScript, and Bootstrap 5**.

This project allows users to securely store, manage, and retrieve their account credentials in a protected vault environment.

Passwords stored in the system are **encrypted before saving to the database**, ensuring sensitive data remains protected.

The application provides a clean dashboard where users can:

* Add account credentials
* Securely reveal passwords
* Copy passwords instantly
* Generate strong passwords
* Manage saved accounts

This project is designed as a **secure backend development portfolio project** demonstrating authentication security, encryption practices, and modern UI development.

---

# ✨ Features

* 🔐 Secure user authentication system
* 🔑 Password hashing using `password_hash()` and `password_verify()`
* 🛡 Encrypted password storage
* 🧾 Secure session handling
* ⚡ CSRF protection for forms
* 👁 Password reveal system
* 📋 Copy password to clipboard
* 🔎 Search accounts
* 🎨 Modern Bootstrap dashboard UI
* 🧰 Password generator
* 📱 Responsive design

---

# 🧑‍💻 Tech Stack

* PHP
* MySQL
* JavaScript
* Bootstrap 5
* HTML5
* CSS3
---

# ⚙️ Required PHP Extensions

The following PHP extensions must be **enabled in your `php.ini` file**.

```
extension=pdo_mysql
extension=openssl
extension=sodium
extension=mbstring
extension=json
extension=session
```

### How to Enable Extensions (XAMPP)

1. Open:

```
xampp/php/php.ini
```

2. Find the extension and remove `;`

Example:

```
;extension=sodium
```

Change to:

```
extension=sodium
```

3. Restart Apache from XAMPP Control Panel.

---

# 🗄 Database Setup

1. Create a database in MySQL:

```
password_manager
```

2. Import the database file:

```
schema.sql
```

You can import it using **phpMyAdmin** or MySQL CLI.

---

# ⚙️ Project Installation

### Step 1 — Clone the Repository

```
git clone https://github.com/yourusername/password-manager.git
```

### Step 2 — Move Project

Place the project inside your web server directory:

```
xampp/htdocs/
```

Example:

```
xampp/htdocs/password-manager
```

### Step 3 — Import Database

Import the file:

```
schema.sql
```

### Step 4 — Configure Database Connection

Open:

```
conn/conn.php
```

Update database credentials:

```
$host = "localhost";
$dbname = "password_manager";
$username = "root";
$password = "";
```

### Step 5 — Run Project

Open browser:

```
http://localhost/password-manager
```

---

# 📁 Project Structure

```
password-manager
│
├── assets
│   ├── css
│   ├── js
│   └── images
│
├── conn
│   └── conn.php
│
├── endpoint
│   ├── add-account.php
│   ├── delete-account.php
│   ├── update-account.php
│   ├── login.php
│   └── add-user.php
│
├── partials
│   ├── header.php
│   ├── footer.php
│   └── modal.php
│
├── home.php
├── index.php
└── schema.sql
```

---

# 🔒 Security Features

This project implements multiple security practices:

* Password hashing using **bcrypt**
* Encrypted password storage
* CSRF protection
* Session regeneration on login
* Secure AJAX password reveal endpoint
* Prepared SQL statements to prevent SQL injection

---

# 🖼 Screenshots

### Dashboard

Add your screenshot here.

```
/screenshots/dashboard.png
```

---

# 🚀 Future Improvements

Possible future improvements:

* Dark mode support
* Two-factor authentication (2FA)
* Browser extension integration
* Password breach detection
* Vault export / import
* Laravel version

---

# 👨‍💻 Author

Developed by a **Full Stack Web Application Developer** specializing in secure backend development, Laravel architecture, and scalable web applications.

---

# ⭐ Support

If you find this project helpful, consider giving it a **star ⭐ on GitHub**.


<img width="1320" height="565" alt="image" src="https://github.com/user-attachments/assets/53e8f947-4f32-48d6-a0bb-2e0ac9c0c6d4" />
