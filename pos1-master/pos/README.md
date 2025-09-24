# POS System (PHP/MySQL)

A simple Point of Sale (POS) web application built with PHP, MySQL, Bootstrap, and Chart.js. This system allows you to manage sales, products, customers, suppliers, inventory, and visualize sales data.

## Features

- User authentication (admin/cashier)
- Sales processing and invoice management
- Product, customer, and supplier management
- Inventory tracking
- Sales reports and monthly/daily sales visualization (Chart.js)
- Responsive dashboard UI (Bootstrap)

## Folder Structure

- `main/` — Main application code (PHP files, CSS, JS, etc.)
- `main/css/` — Stylesheets (Bootstrap, Font Awesome, custom CSS)
- `main/js/` — JavaScript libraries
- `main/img/`, `main/images/`, `main/ico/`, `main/font/` — Assets
- `main/sales_visualization.php` — Sales charts and analytics
- `main/products.php`, `main/sales.php`, etc. — Core modules
- `connect.php` — Database connection

## Database

- MySQL database required.
- Example table: `sales_order` (see `sales.sql` for schema)

## Installation

1. Clone or copy the project to your web server directory (e.g., `htdocs` for XAMPP).
2. Import the `sales.sql` file into your MySQL database.
3. Update database credentials in `connect.php` if needed.
4. Start Apache and MySQL (XAMPP or similar).
5. Access the app via `http://localhost/pos1/pos/main/index.php`.

## Usage

- Log in as admin or cashier.
- Use the navigation bar to access Sales, Products, Customers, Suppliers, Reports, and Visualization.
- Add/edit/delete products, process sales, and view analytics.

## Dependencies

- PHP 5.6+
- MySQL
- Bootstrap 3
- Font Awesome
- Chart.js
- jQuery

## Credits

- Developed by [Your Name/Team]
- Bootstrap, Font Awesome, Chart.js, jQuery (open source)

---

For any issues or questions, please contact the developer or open an issue.
