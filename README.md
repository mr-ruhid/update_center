# 🚀 RJ Site Updater & Plugin Center (v1)

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/License-Proprietary-red?style=for-the-badge)

**RJ Site Updater** is a centralized management system based on Laravel designed for software version control, changelog distribution, and plugin sales. It simplifies the process of pushing updates to client sites and managing a marketplace for extensions.

---

## 🌟 Support & Donate

If you find this project useful or want to support future updates, consider buying me a coffee!

<a href="https://kofe.al/ruhidjavadoff">
  <img src="https://img.shields.io/badge/Support%20on-Kofe.al-6f4e37?style=for-the-badge&logo=buy-me-a-coffee&logoColor=white" alt="Support on Kofe.al" height="40">
</a>
<a href="https://paypal.me/ruhidjavadoff">
  <img src="https://img.shields.io/badge/Donate%20via-PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="Donate via PayPal" height="40">
</a>

---

## 🌐 Live Demo

You can access the active system via the link below:

### 👉 **[https://pos.ruhidjavadov.site](https://pos.ruhidjavadov.site)**

*(Note: For local testing, once installed, the default address is http://127.0.0.1:8000/)*

---

## 🚀 Key Features

### 1. Version Management (Update Center)
* **Release Management:** Publish new software versions effortlessly.
* **Flexible Downloads:** Supports separate downloads for **Update** packages (changed files only) and **Full** scripts.
* **Changelogs:** Visual gallery and detailed notes for every version.
* **API Connectivity:** Client sites can check for versions and receive update notifications automatically via API.

### 2. Plugin Center
* **Extension Management:** Centralized hub for managing system plugins.
* **Monetization:** Support for both **Free** and **Paid** plugins.
* **Visual Assets:** Custom icons and description fields for each plugin.

### 3. Payment & Sales System
* **Cryptomus Integration:** Automated crypto payments (USDT, etc.).
* **Stripe Support:** Secure credit card payments.
* **Sales History:** Admin dashboard to track successful and pending transactions.

### 4. Security
* **2FA (Two-Factor Authentication):** Email-based security code requirement for admin login.
* **Access Logs:** Detailed logs recording IP addresses and device info for login attempts.
* **Email Alerts:** Instant notifications to the admin upon successful login.

### 5. Content & Language Management
* **Dynamic Translation:** Database-driven translation system supporting **AZ, EN, RU, TR**.
* **Menu Manager:** Drag & Drop interface for dynamic navigation menus.
* **Home Editor:** Edit Hero section text and slider images directly from the admin panel.
* **Product Images:** Barcode-based image storage distributed via API/Links.

---

## 🛠 Tech Stack

| Category | Technology |
| :--- | :--- |
| **Framework** | Laravel 11.x |
| **Frontend** | Tailwind CSS, AlpineJS, FontAwesome 6 |
| **Database** | MySQL |
| **Tools** | SortableJS, CKEditor 5, Ngrok (for local testing) |

---

## ⚙️ Installation Guide

Follow these steps to set up the project locally:

1.  **Clone the Repository**
    ```bash
    git clone [https://github.com/your-username/rj-updater.git](https://github.com/your-username/rj-updater.git)
    cd rj-updater
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run dev
    ```

3.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Configuration**
    * Create a database in your local MySQL.
    * Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in your `.env` file.
    * Run migrations and seeders:
        ```bash
        php artisan migrate --seed
        ```

5.  **Filesystem**
    ```bash
    php artisan storage:link
    ```

6.  **Run Server**
    ```bash
    php artisan serve
    ```

---

## 🔑 Default Admin Credentials

* **URL:** `/login`
* **Email:** `admin@example.com`
* **Password:** `password`

> ⚠️ **Note:** Please change these credentials immediately via "Account Settings" after your first login.

---

## 🌐 API Usage

Client applications can check for updates using the following endpoint:

**Endpoint:** `POST /api/v1/check`

**Parameters:**
* `api_key` (string)
* `current_version` (string)

**Sample Response (JSON):**
```json
{
  "update_available": true,
  "data": {
    "version": "3.0.0",
    "action_url": "[https://yoursite.com/updates](https://yoursite.com/updates)",
    "notes": "New features added and bugs fixed."
  }
}
📞 Contact & Support
If you have questions or need custom development, feel free to reach out:

Email: ruhidjavadoff@gmail.com

Phone: +994 50 663 60 31

Developer: Ruhid Javadov

🛡 License
This project is intended for personal use. All rights reserved. Copyright © 2026 RJ Site Updater.
