# IT0049 POS — TFA 2, TFA 3 and TFA 4 (CodeIgniter 4)

| | |
|---|---|
| **Course** | IT0049 – Web System Technologies |
| **Activities** | TFA 2: From Arrays to a Real Database · TFA 3: Forms, Validation, and File Upload · TFA 4: Sessions and Authentication |
| **Student** | *(your name)* |
| **Section** | *(your section)* |
| **Professor** | *(professor name)* |
| **Live demo** | https://your-tfa3-subdomain.infinityfreeapp.com/ |
| **Repository** | https://github.com/jppascual-ux/IT0049-TFA3-CodeIgniter-POS |
| **Demo login** | Username `admin` · Password `password123` (every sample user uses `password123`) |

One CodeIgniter 4 Point-of-Sale app that contains all three activities. The **home page lets you
choose TFA 2, TFA 3 or TFA 4**. Each activity has its own page that explains what it added, links
straight to the live feature, lists the requirements it meets, and shows the key code.

| Page | URL | Login needed |
|---|---|---|
| Choose an activity | `/` | No |
| TFA 2 – From arrays to a real database | `/tfa2` | No |
| TFA 3 – Making it editable | `/tfa3` | No |
| TFA 4 – Who's allowed in? (with a live session panel) | `/tfa4` | No |
| Log in | `/login` | No |
| Customer Accounts (list, new, edit) | `/customers` | **Yes** (TFA 4) |
| User Accounts (list, new, edit, avatar) | `/users` | **Yes** (TFA 4) |

---

## Table of Contents

1. [What each activity adds](#1-what-each-activity-adds)
2. [Interface design](#2-interface-design)
3. [Screenshots](#3-screenshots)
4. [Requirements](#4-requirements)
5. [Run it locally (XAMPP)](#5-run-it-locally-xampp)
6. [Test checklist](#6-test-checklist)
7. [Push to GitHub](#7-push-to-github)
8. [Deploy to the live site (InfinityFree)](#8-deploy-to-the-live-site-infinityfree)
9. [Database](#9-database)
10. [How the code works](#10-how-the-code-works)
11. [Routes](#11-routes)
12. [Project structure](#12-project-structure)
13. [Requirements checklists (TFA 2, 3, 4)](#13-requirements-checklists)
14. [Troubleshooting](#14-troubleshooting)
15. [References](#15-references)

---

## 1. What each activity adds

### TFA 2 — From arrays to a real database
- Customers and users moved from static PHP arrays into a **MySQL** database (`pos_db`).
- `CustomerModel` and `UserModel` read records with **Query Builder** (`findAll()`), with no raw SQL.
- The Customer Accounts and User Accounts pages display the database records.

### TFA 3 — Making it editable
- **New Customer** form (`/customers/new`): full name required, email required and valid.
- **New User** form (`/users/new`): username required and unique, full name required.
- **Edit pages** for both, pre-filled with the saved record and updated on submit.
- Invalid input is rejected with clear messages, and **what you typed stays in the form**.
- **Avatar upload** on Edit User:
  - Only JPG or PNG up to 2 MB is accepted.
  - The Image service crops it into a **200 × 200 thumbnail**, stored in `public/uploads/avatars/`.
  - **Only the filename** is saved in the `avatar` column.
  - Users without a picture show a placeholder.

### TFA 4 — Who's allowed in?
- A `password` column on `users`. Every user's password is stored with **`password_hash()`**.
- A **login page** that verifies the password with **`password_verify()`** and **starts a session**.
- **`AuthFilter`**, a CodeIgniter Filter registered on **every** customer and user route
  (list, new, edit and the POST actions). It sends logged-out visitors to `/login`.
- **Logout** destroys the session and returns to the login page.
- New users must set a password (min. 8 characters, typed twice). On edit it's optional;
  leaving it blank keeps the current one.
- **Migration and seeder files** in `app/Database/`, plus a SQL export in `database/`.
- **Security extras:**
  - The session ID is regenerated on login.
  - Wrong usernames and wrong passwords get the same error message.
  - Login attempts are limited to 10 per minute.
  - Protected pages aren't cached by the browser.
  - Logout is CSRF-protected.
  - After logging in, you're sent back to the page you originally requested.

## 2. Interface design

The whole interface was redesigned with a clean, minimal look inspired by Apple's style. It is
built **mobile-first**: the base styles target phones, and the layout widens at 735 px and 1069 px.

- **Layout:**
  - A frosted-glass navigation bar. On phones it becomes a full-screen menu with links that slide in.
  - The home page shows a tile for each activity. On wide screens TFA 4 spans the full width, with TFA 3 and TFA 2 side by side below it.
  - On phones, the customer and user tables turn into contact-style rows with avatars. On wider screens they become a normal table.
- **Animations:**
  - Each activity tile plays a short illustration when it scrolls into view:
    - **TFA 2:** lines of PHP array code turn into database rows.
    - **TFA 3:** an email field catches a typo, shows the error, gets fixed and saves.
    - **TFA 4:** password dots fill in and a padlock unlocks.
  - The headline fades in when a page loads.
  - Success messages appear as a black pill that expands from the top of the screen.
  - Pages fade into each other when you navigate (in supported browsers).
  - The login padlock closes when the page opens.
  - Invalid fields shake once.
- **Small touches:**
  - Live search on the list pages.
  - Show/hide buttons on password fields.
  - Drag-and-drop avatar upload with an instant preview.
  - A "Fill in" button for the demo account.
  - A loading spinner on submit buttons.
  - Highlighting of a newly added row.
- **Accessibility and comfort:**
  - Automatic **dark mode**.
  - **Reduced motion** settings are respected.
  - Visible keyboard focus and a skip link.
  - Labelled form errors (`aria-invalid`, `aria-describedby`).
- **Implementation:**
  - All styles are in `public/assets/css/app.css` and all behaviour is in `public/assets/js/app.js`.
  - No frameworks or build step.
  - Every page and form still works with JavaScript turned off, and all validation is done on the server.

## 3. Screenshots

### Home: choose an activity
| Desktop | Phone |
|---|---|
| ![Home desktop](docs/screenshots/home-desktop.png) | ![Home phone](docs/screenshots/home-mobile.png) |

### Activity pages
| TFA 2 | TFA 3 | TFA 4 |
|---|---|---|
| ![TFA 2](docs/screenshots/tfa2-desktop.png) | ![TFA 3](docs/screenshots/tfa3-desktop.png) | ![TFA 4](docs/screenshots/tfa4-desktop.png) |

### TFA 4: access control and sessions
| Logged out → sent to login | Wrong password | Logged out |
|---|---|---|
| ![Login required](docs/screenshots/login-required-mobile.png) | ![Login error](docs/screenshots/login-error-desktop.png) | ![Logged out](docs/screenshots/logged-out-desktop.png) |

**Live session panel on `/tfa4`**, reading back what the login stored in the session:
![Session panel](docs/screenshots/tfa4-session.png)

**Passwords are stored as `password_hash()` values only:**
![Password hashes](docs/screenshots/tfa4-db-password-hashes.png)

### TFA 2: records from MySQL
| Customers (desktop) | Customers (phone) | Users (desktop) |
|---|---|---|
| ![Customers desktop](docs/screenshots/customers-desktop.png) | ![Customers phone](docs/screenshots/customers-mobile.png) | ![Users desktop](docs/screenshots/users-desktop.png) |

### TFA 3: forms, validation, avatar upload
| Invalid email (entries kept) | New user errors | Edit user + avatar |
|---|---|---|
| ![Customer errors](docs/screenshots/customer-errors-mobile.png) | ![User errors](docs/screenshots/user-errors-desktop.png) | ![Edit user](docs/screenshots/user-edit-desktop.png) |

### Interface details
| Success toast | Live search | Phone menu | 404 page |
|---|---|---|---|
| ![Toast](docs/screenshots/toast-mobile.png) | ![Search](docs/screenshots/search-mobile.png) | ![Menu](docs/screenshots/menu-mobile.png) | ![404](docs/screenshots/404-mobile.png) |

## 4. Requirements

| Requirement | Notes |
|---|---|
| PHP **8.1+** | XAMPP 8.2 or 8.3 recommended |
| PHP extensions `intl`, `mbstring`, `mysqli`, `gd` | `gd` is needed for avatar thumbnails |
| MySQL 5.7+ / MariaDB 10.3+ | Included in XAMPP |
| Git + free GitHub account | https://git-scm.com · https://github.com |
| **Composer is not required** | CodeIgniter 4.6 is included in `system/` |

All tools are free: XAMPP, VS Code, Git, GitHub, InfinityFree, FileZilla.

## 5. Run it locally (XAMPP)

1. **Install the tools** (one time):
   - XAMPP: https://www.apachefriends.org
   - VS Code: https://code.visualstudio.com
   - Git: https://git-scm.com
   - FileZilla Client: https://filezilla-project.org
   - Then add `C:\xampp\php` to Windows **Path**: *Edit the system environment variables →
     Environment Variables → Path → Edit → New*. Reopen VS Code afterwards.
2. **Open the project:**
   1. Extract the zip outside OneDrive, e.g. `C:\Projects\IT0049-TFA3-CodeIgniter-POS`.
   2. In VS Code choose **File → Open Folder**, then **Terminal → New Terminal**.
3. **Check PHP:**
   1. Run `php -v` (must be 8.1+) and `php -m` (must list `gd`, `intl`, `mbstring`, `mysqli`).
   2. If one is missing, open `C:\xampp\php\php.ini` and remove the `;` in front of
      `extension=gd` / `extension=intl`.
   3. Restart Apache.
4. **Start XAMPP:** click **Start** on **Apache** and **MySQL**.
5. **Create the database** (choose one):
   - **SQL import:**
     1. Open http://localhost/phpmyadmin → **Import**.
     2. Choose `database/pos_db.sql` → **Import**.
   - **Migrations + seeders:**
     1. In phpMyAdmin create an empty `pos_db` database.
     2. Run:
        ```bash
        php spark migrate
        php spark db:seed DatabaseSeeder
        ```
6. **Check `.env`:** it already matches XAMPP (user `root`, empty password). Add your MySQL
   password if you set one.
7. **Run:**
   ```bash
   php spark serve
   ```
   Open http://localhost:8080. The home page lets you choose an activity. Log in with
   `admin` / `password123`.

## 6. Test checklist

| # | Do this | Expected |
|---|---|---|
| 1 | Open `/`, then `/tfa2`, `/tfa3`, `/tfa4` while logged out | All load; each tile's illustration plays as it scrolls into view |
| 2 | While logged out open `/customers`, `/customers/new`, `/customers/1/edit`, `/users`, `/users/new`, `/users/1/edit` | Each one redirects to `/login` with "Please log in to access that page." |
| 3 | Log in with both fields empty | "Username is required." and "Password is required." |
| 4 | Log in as `admin` / `wrongpass`, then `ghost` / `password123` | The same "Invalid username or password." both times; the username stays filled in |
| 5 | Log out, open `/users/2/edit`, then log in as `admin` / `password123` | You land on `/users/2/edit` |
| 6 | Open `/tfa4` while logged in | The session panel shows `isLoggedIn true`, your user ID, username and login time |
| 7 | **+ New customer** → Save with name `Juan Dela Cruz`, email `juan.delacruz` | Email error appears and the name is still in the box |
| 8 | Fix the email to `juan@email.com` → **Add customer** | The "Customer added." toast appears and the new row is highlighted |
| 9 | **Edit** any customer, clear the name → **Save changes** | "Full Name is required." Then fix it and save |
| 10 | **+ New user**: username `admin`, empty name, password `short`, confirm `other` | Four errors: username taken, name required, password too short, passwords don't match |
| 11 | Create `cashier3` / `Joan Aquino` / `SecretPass1` (twice) | "User added."; in phpMyAdmin the password is a `$2y$10$…` hash |
| 12 | Edit any user without typing a password → **Save changes** | Saved; the password is unchanged |
| 13 | Edit a user and choose a `.gif`, then a PNG over 2 MB | "The profile picture must be a JPG or PNG image." / "…must not be larger than 2 MB." |
| 14 | Edit a user and choose a JPG/PNG under 2 MB | The preview updates instantly; after saving, the round thumbnail shows in User Accounts |
| 15 | **Log out** (top right, or the menu on phones) | Back on `/login` with "You're logged out. Your session was deleted." |
| 16 | After logging out, press **Back** or open `/customers` | Redirected to `/login` |
| 17 | Log in as `cashier3` / `SecretPass1` | Works, so the new user's hash is verified |

## 7. Push to GitHub

This project **replaces the contents of your existing TFA 3 repository**
(`IT0049-TFA3-CodeIgniter-POS`), so that one repo and one site hold TFA 2, 3 and 4.

### A. Update the existing TFA 3 repository (recommended)
1. Open your **existing** local TFA 3 project folder (the one with the hidden `.git` folder) in
   VS Code and open a terminal.
2. Save the original TFA 3 version as a tag so it can still be viewed:
   ```bash
   git tag tfa3-original
   git push origin tfa3-original
   ```
3. In your existing project folder, **delete the `docs/screenshots` folder**. The old screenshots
   aren't used any more, and pasting new files over the folder wouldn't remove them.
4. Extract this zip. Open the extracted `IT0049-TFA3-CodeIgniter-POS` folder, select **everything
   inside it**, and paste it into your existing project folder. Choose **Replace** for all files.
   Keep your own `.env` if your MySQL password differs from the default.
5. Fill in your name, section and professor at the top of this README. Replace
   `your-tfa3-subdomain` with your real subdomain: **Ctrl + Shift + H** → **Replace All**.
6. Commit and push:
   ```bash
   git status            # .env must NOT be listed
   git add .
   git commit -m "TFA4 integrated: login, auth filter, logout, activity picker and redesigned UI"
   git push
   git tag tfa4
   git push origin tfa4
   ```

### B. Brand-new repository instead
```bash
git init
git add .
git commit -m "IT0049 POS: TFA2, TFA3 and TFA4"
git branch -M main
git remote add origin https://github.com/jppascual-ux/IT0049-TFA3-CodeIgniter-POS.git
git push -u origin main
```
- If you see `remote origin already exists`, run
  `git remote set-url origin https://github.com/jppascual-ux/IT0049-TFA3-CodeIgniter-POS.git`
  and push again.
- If Git can't push, create the empty repo on GitHub first (Public, no README, no `.gitignore`,
  no license).

### Verify
Open the repo in an incognito window and check:
- The README shows the screenshots.
- `app/Filters/AuthFilter.php`, `app/Database/Migrations`, `app/Database/Seeds` and `database/` are present.
- `.env` is **not** listed.

## 8. Deploy to the live site (InfinityFree)

### A. Update your existing TFA 3 site (keeps your data)
1. **Database:**
   1. InfinityFree Control Panel → **MySQL Databases** → **Admin** (phpMyAdmin).
   2. Select your database → **SQL** tab.
   3. Paste the contents of **`database/tfa4_add_password_column.sql`** → **Go**.
   4. This adds the `password` column and gives every existing user the password `password123`.
      No records are deleted.
2. **Connect FileZilla** with your FTP details (port 21) and turn on
   **Server → Force showing hidden files**.
3. **Upload into `htdocs/`, overwriting the old files:**
   - `app/`
   - `public/`
   - `database/`
   - `docs/`
   - `README.md`
   - `.gitignore`

   **Do not upload `.env`.** The server keeps its own `.env` with the InfinityFree database
   details. `system/` and the root `.htaccess` haven't changed.
4. **Permissions:**
   1. Right-click `htdocs/writable` → **File permissions** → `755`.
   2. Tick **Recurse into subdirectories** → **Apply to directories only** → OK.
   3. Repeat for `htdocs/public/uploads`.

   Login can't stay logged in unless `writable/session` is writable.
5. **Test:**
   1. Open the live URL and choose an activity.
   2. Open `/customers` while logged out. It should send you to the login page.
   3. Log in with `admin` / `password123`.
   4. Check `/tfa4` for the session panel.
   5. Log out.

### B. Fresh site (new InfinityFree account)
1. **Create the account:**
   1. Register at https://www.infinityfree.com → **Create Account** → free subdomain.
   2. Wait for **Active**.
   3. **Control Panel → Select PHP Version** → highest 8.x.
2. **Create the database:**
   1. **MySQL Databases** → create `pos_db`.
   2. Note the hostname (`sqlXXX.infinityfree.com`), database name and username. The password is
      your FTP password.
3. **Import the data:**
   1. **Admin** → select the database → **Import**.
   2. Choose **`database/pos_db_tables_only.sql`** → **Import**.
4. **Production settings:**
   1. Fill in `.env.infinityfree.example` with those values and your live URL (keep the
      trailing `/`).
   2. Save it as `.env.production`.
5. **Upload:**
   1. In FileZilla open `htdocs/` and delete its default files.
   2. Upload everything **except** `.env`, `.env.production` and `.git`, including the root
      `.htaccess`.
   3. Then upload `.env.production` and rename it on the server to `.env`.
6. **Permissions:** set `755` (recursive, directories only) on `htdocs/writable` and
   `htdocs/public/uploads`.
7. **Test:** run tests 1, 2, 5, 6 and 15 from Section 6 on the live URL.
8. **Allow time:** a new subdomain can take up to an hour to start working.

## 9. Database

```sql
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,          -- TFA 4: password_hash() value
  created_at DATETIME NOT NULL,
  avatar VARCHAR(255) NULL DEFAULT NULL    -- TFA 3: image filename only
);
```

| File | Use |
|---|---|
| `database/pos_db.sql` | Full export: creates `pos_db`, both tables and sample data (local) |
| `database/pos_db_tables_only.sql` | The same without `CREATE DATABASE` (InfinityFree) |
| `database/tfa4_add_password_column.sql` | Upgrades a TFA 3 database to TFA 4 without losing records |
| `database/tfa3_add_avatar_column.sql` | Upgrades a TFA 2 database to TFA 3 |
| `app/Database/Migrations/*` | `CreateCustomersTable`, `CreateUsersTable` (`php spark migrate`) |
| `app/Database/Seeds/*` | `CustomerSeeder`, `UserSeeder` (hashes each password), `DatabaseSeeder` |

Sample logins (password `password123` for all): `admin`, `cashier1`, `cashier2`, `manager`,
`inventory`, `support`.

## 10. How the code works

### Request flow for a protected page
```
Browser ─GET /customers─▶ Router ─▶ AuthFilter::before()
                                       │
                  session('isLoggedIn') === true ?
                       │yes                    │no
                       ▼                       ▼
            Customers::index()       remember URL → redirect /login
            CustomerModel->findAll()
                       │
            views/customers/index.php ─▶ AuthFilter::after() adds no-store headers
```

### TFA 2 — Model + Query Builder
```php
// app/Controllers/Customers.php
$data['customers'] = $this->customerModel->orderBy('id', 'ASC')->findAll();
return view('customers/index', $data);
```

### TFA 3 — validate, redisplay, store avatar
```php
if (! $this->validate($this->customerModel->formRules())) {
    return redirect()->to('customers/new')->withInput();   // errors + typed values
}
$this->customerModel->insert($this->formData());
return redirect()->to('customers')->with('success', 'Customer added.');

// Users::storeAvatar()
$filename = $file->getRandomName();
service('image')->withFile($file->getTempName())->fit(200, 200, 'center')
    ->save(FCPATH . 'uploads/avatars/' . $filename, 85);
$data['avatar'] = $filename;                                // filename only
```

### TFA 4 — hash, verify, filter, logout
```php
// UserModel: beforeInsert/beforeUpdate callback
$data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

// Auth::attempt()
if ($user === null || ! password_verify($password, $user['password'])) { /* generic error */ }
$session->regenerate(true);
$session->set(['isLoggedIn' => true, 'user_id' => $user['id'], 'username' => $user['username'], ...]);

// app/Config/Filters.php
'auth' => \App\Filters\AuthFilter::class,

// app/Config/Routes.php
$routes->group('', ['filter' => 'auth'], static function ($routes) { /* every customers/* and users/* route */ });

// Auth::logout()
session()->destroy();
return redirect()->to('login?logged_out=1');
```

### Activity pages
`App\Libraries\ActivityCatalog` holds each activity's content: summary, links to try,
learning outcomes, requirements and code excerpts. `Activities::show($n)` renders it with
`activities/show.php`, and `Home::index()` uses it for the activity tiles.

## 11. Routes

| Method | URL | Controller | Filter |
|---|---|---|---|
| GET | `/` | `Home::index` | public |
| GET | `/tfa2`, `/tfa3`, `/tfa4` | `Activities::show` | public |
| GET | `/login` | `Auth::login` | public |
| POST | `/login` | `Auth::attempt` | public |
| POST | `/logout` | `Auth::logout` | public (CSRF) |
| GET | `/customers` | `Customers::index` | **auth** |
| GET | `/customers/new` | `Customers::new` | **auth** |
| POST | `/customers` | `Customers::create` | **auth** |
| GET | `/customers/{id}/edit` | `Customers::edit` | **auth** |
| POST | `/customers/{id}/update` | `Customers::update` | **auth** |
| GET | `/users` | `Users::index` | **auth** |
| GET | `/users/new` | `Users::new` | **auth** |
| POST | `/users` | `Users::create` | **auth** |
| GET | `/users/{id}/edit` | `Users::edit` | **auth** |
| POST | `/users/{id}/update` | `Users::update` | **auth** |

## 12. Project structure

```
app/
├── Config/          App.php (timezone Asia/Manila), Filters.php ('auth' + CSRF), Routes.php
├── Controllers/     Home, Activities, Auth, Customers, Users, BaseController
├── Database/
│   ├── Migrations/  CreateCustomersTable, CreateUsersTable
│   └── Seeds/       CustomerSeeder, UserSeeder, DatabaseSeeder
├── Filters/         AuthFilter.php
├── Helpers/         ui_helper.php (icons, brand mark, initials avatars, partial())
├── Libraries/       ActivityCatalog.php (content of the activity pages)
├── Models/          CustomerModel.php, UserModel.php
└── Views/
    ├── layouts/main.php            navigation, phone menu, toast, footer
    ├── home.php                    choose an activity
    ├── activities/show.php         TFA 2 / 3 / 4 pages (+ live session panel)
    ├── auth/login.php
    ├── customers/index.php, form.php
    ├── users/index.php, form.php
    ├── partials/                   field, error summary, illustrations, lock icon
    └── errors/html/                styled 404 and error pages
database/                           SQL export + upgrade scripts
docs/screenshots/                   README images
public/
├── assets/css/app.css              design system (mobile-first, dark mode, animations)
├── assets/js/app.js                menu, reveals, toast, search, avatar preview
├── assets/img/                     favicon, avatar placeholder
└── uploads/avatars/                thumbnails (writable)
system/                             CodeIgniter 4.6
writable/                           sessions, cache, logs (writable)
```

## 13. Requirements checklists

### TFA 2
| Requirement | Evidence |
|---|---|
| `.env` database settings for local MySQL | `.env.example` |
| `customers` and `users` tables from the schema | `database/pos_db.sql`, migrations |
| At least 5 records per table | 6 each |
| `CustomerModel` and `UserModel` | `app/Models/` |
| Controllers use the Models (Query Builder) | `findAll()` in both `index()` methods |
| Views show the database records | `/customers`, `/users` |

### TFA 3
| Requirement | Evidence |
|---|---|
| `/customers/new`: full name required, email required + valid | `CustomerModel::formRules()` |
| `/users/new`: username required + unique, full name required | `UserModel::formRules()` |
| Edit pages pre-fill and update | `edit()` / `update()` in both controllers |
| Errors shown, entries preserved | `withInput()` + `old()` |
| `avatar` column; JPG/PNG ≤ 2 MB; thumbnail; public folder; filename only | `UserModel::avatarRules()`, `Users::storeAvatar()` |
| Avatar or placeholder on User Accounts | `UserModel::avatarUrl()` |

### TFA 4
| Requirement | Evidence |
|---|---|
| `password` column + `password_hash()` for every existing user | `tfa4_add_password_column.sql`, `UserSeeder`, SQL export |
| Login verifies with `password_verify()` and starts a session | `Auth::attempt()` |
| Filter on Customer and User routes, including new and edit forms | `AuthFilter`, `auth` route group (all 10 routes) |
| Logout destroys the session → login page | `Auth::logout()` |
| Logged out → redirected; logged in → page loads | Tests 2 and 5 |
| Repo includes database export **or** migrations and seeders | Both are included |

### Rubric
| Criterion | How it's addressed |
|---|---|
| Functionality & Requirements (40) | Every TFA 2–4 feature works and is linked from the activity pages |
| Code Structure & Organization (25) | Separate controllers, filter, models, views, partials, helper, library, migrations and seeders; one CSS and one JS file |
| Data / Validation / Authentication (20) | Query Builder only; server-side validation with preserved input; bcrypt + `password_verify()`; session regeneration; filter on every protected route |
| Documentation & Submission (15) | This README with screenshots and steps, SQL + migrations, live link |

## 14. Troubleshooting

| Problem | Fix |
|---|---|
| Login always returns to the login page | Make `writable/` writable (`755`, recursive) |
| "Invalid username or password" with `password123` | Run `database/tfa4_add_password_column.sql`, or re-import `pos_db_tables_only.sql` |
| `Unknown column 'password'` | Run `database/tfa4_add_password_column.sql` |
| "The action you requested is not allowed." | The form's security token expired. Reload and submit again |
| "Too many login attempts" | Wait one minute |
| Avatars show the placeholder on the live site | Upload `public/uploads/avatars/` and set `755` on `public/uploads` |
| Styles missing on the live site | `public/assets/` wasn't uploaded, or `app.baseURL` in the server `.env` is wrong (keep the trailing `/`) |
| 404 on every page on the live site | The root `.htaccess` is missing. Enable hidden files in FileZilla and upload it |
| Blank page / 500 on the live site | Temporarily set `CI_ENVIRONMENT = development` in the server `.env` to see the error |
| Animations don't play | Your device has "Reduce motion" turned on. That's intentional; everything still works |
| `remote origin already exists` | `git remote set-url origin <repo URL>` |
| Git errors in OneDrive ("unable to unlink") | Pause OneDrive syncing or move the project to `C:\Projects\` |

## 15. References

- CodeIgniter 4 User Guide — Models, Query Builder, Validation, Uploaded Files, Image
  Manipulation, Sessions, Controller Filters, Migrations, Seeding:
  https://codeigniter4.github.io/userguide/
- PHP Manual — `password_hash()`: https://www.php.net/manual/en/function.password-hash.php
- PHP Manual — `password_verify()`: https://www.php.net/manual/en/function.password-verify.php
- MDN — `prefers-reduced-motion`: https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion
