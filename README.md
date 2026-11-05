# Shopware 6 Virtual Host Environment (Zander Project)

This folder is configured for an individual local Shopware 6 environment using the official **Shopware Web Installer** (`shopware-installer.phar.php`).

---

## Domain & Virtual Host Details

- **Domain Name:** `http://shopware6-zander.local`
- **Initial Document Root (Installation phase):** `/var/www/html/shopware6-zander`
- **Final Document Root (Production phase):** `/var/www/html/shopware6-zander/public`
- **Web Installer File:** `shopware-installer.phar.php`

---

## Quick Setup Steps

### Step 1: Activate Virtual Host & Services
Run the automated setup script with `sudo`:
```bash
sudo ./setup-vhost.sh
```

This script automatically:
1. Copies `shopware6-zander.local.conf` to `/etc/apache2/sites-available/`
2. Adds `127.0.0.1 shopware6-zander.local www.shopware6-zander.local` to `/etc/hosts`
3. Enables Apache modules (`rewrite`, `headers`, `expires`) and enables the site (`a2ensite`)
4. Sets the correct web server permissions (`www-data`)
5. Starts / restarts Apache and MySQL services

---

### Step 2: Run the Shopware 6 Web Installer
Open your web browser and navigate to:
```
http://shopware6-zander.local/shopware-installer.phar.php
```
Follow the web setup wizard:
- **System Requirements Check:** Ensure all checks pass.
- **Database Configuration:**
  - **Host:** `localhost` (or `127.0.0.1:3306`)
  - **User:** `root`
  - **Password:** `NewRoot@Pass123` (or your local MySQL root password)
  - **Database name:** `shopware6_zander` (you can specify this and the installer will create it)
- Complete shop setup (admin username, password, currency, language).

---

### Step 3: Switch DocumentRoot to `/public`
Once the installation completes, switch the Apache DocumentRoot to the secure `/public` directory:
```bash
sudo ./switch-to-public.sh
```

Your shop will now be accessible at:
- **Storefront:** `http://shopware6-zander.local`
- **Administration:** `http://shopware6-zander.local/admin`

