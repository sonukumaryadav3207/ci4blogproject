# CodeIgniter 4 Setup Guide (XAMPP + Composer)

This README file documents the step-by-step setup process for running a CodeIgniter 4 project on a local Windows machine using XAMPP and Composer.

---

## ✅ Requirements

* XAMPP (PHP >= 8.0)
* Composer
* Windows 10 or 11

---

## 🛠️ 1. XAMPP Installation

* Install XAMPP to a **non-system directory** like `F:\xampp` or `D:\xampp` to avoid User Account Control (UAC) issues.
* Do **not** install in `C:\Program Files`.
* Start Apache and MySQL via XAMPP Control Panel.

---

## 🧰 2. Composer Installation

* Download Composer from [getcomposer.org](https://getcomposer.org/)
* During installation:

  * ✅ Choose: **Install for all users** (Recommended)
  * Select the PHP executable: `F:\xampp\php\php.exe`
  * If asked about proxy, choose **No** unless you're behind a corporate proxy.

---

## 📦 3. Install CodeIgniter 4

```bash
cd F:/xampp/htdocs
composer create-project codeigniter4/appstarter ci4blog
```

If you see errors about `ext-intl`:

* Open `php.ini` (from `F:/xampp/php/php.ini`)
* Uncomment the following line:

  ```
  extension=intl
  ```
* Restart Apache and try again.

If you see ZIP-related issues:

* Uncomment or add in `php.ini`:

  ```
  extension=zip
  ```
* Restart Apache and try again.

---

## ⚙️ 4. Running the Project

Use the built-in server:

```bash
cd F:/xampp/htdocs/ci4blog
php spark serve
```

Access at:

```
http://localhost:8080
```

---

## 🌐 5. Virtual Host Setup (Optional)

To use a custom domain like `http://ci4blogproject.local`:

### a. Edit Apache Config

Edit: `C:/xampp/apache/conf/extra/httpd-vhosts.conf`

```apache
<VirtualHost *:80>
    DocumentRoot "F:/xampp/htdocs/ci4blog/public"
    ServerName ci4blog.local
    <Directory "F:/xampp/htdocs/ci4blog/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### b. Edit Windows Hosts File

Edit: `C:\Windows\System32\drivers\etc\hosts`
Add:

```
127.0.0.1    ci4blog.local
```

### c. Restart Apache

Then access:

```
http://ci4blogproject.local
```

---

## 🧩 6. Common Issues

### ❌ MySQL Shutdown Unexpectedly

* Close XAMPP
* Start MySQL manually from the `xampp/mysql/bin/mysqld.exe`
* Avoid deleting or renaming the `data` folder

### ❌ "system/bootstrap.php" error

* This means you're using an old CodeIgniter 4 structure.
* Delete the folder and re-run `composer create-project` cleanly.

---

## 📁 Migration Example

Example `CreateUsersTable.php`:

```php
$this->forge->addField([
    'id' => [
        'type' => 'INT',
        'unsigned' => true,
        'auto_increment' => true
    ],
    'name' => [ 'type' => 'VARCHAR', 'constraint' => 100 ],
    'username' => [ 'type' => 'VARCHAR', 'constraint' => 100, 'unique' => true ],
    'email' => [ 'type' => 'VARCHAR', 'constraint' => 150, 'unique' => true ],
    'password' => [ 'type' => 'VARCHAR', 'constraint' => 255 ],
    'picture' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
    'bio' => [ 'type' => 'TEXT', 'null' => true ],
    'created_at' => [ 'type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'null' => true ],
    'updated_at' => [ 'type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'null' => true, 'on_update' => 'CURRENT_TIMESTAMP' ],
]);
```

---

## 🌱 Seeding the Users Table

### 1. Create the Seeder

```bash
php spark make:seeder UserSeeder
```

### 2. Edit `UserSeeder.php`

Located at `app/Database/Seeds/UserSeeder.php`

```php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'name'      => $faker->name,
                'username'  => $faker->userName,
                'email'     => $faker->unique()->safeEmail,
                'password'  => password_hash('password', PASSWORD_DEFAULT),
                'picture'   => $faker->imageUrl(200, 200, 'people'),
                'bio'       => $faker->sentence,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('users')->insertBatch($data);
    }
}
```

### 3. Run the Seeder

```bash
php spark db:seed UserSeeder
```

This will insert 10 dummy users into your `users` table.

---

## ✅ You're Ready!

Your CodeIgniter 4 project is now set up and ready to go.

---

Let me know if you want to add authentication setup, email verification, or model/controller examples next!
