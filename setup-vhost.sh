#!/bin/bash
set -e

echo "========================================="
echo " Setting up Shopware 6 Virtual Host"
echo "========================================="

DOMAIN="shopware6-zander.local"
VHOST_CONF="shopware6-zander.local.conf"
SOURCE_CONF="/var/www/html/shopware6-zander/$VHOST_CONF"
TARGET_CONF="/etc/apache2/sites-available/$VHOST_CONF"

# 1. Copy Virtual Host configuration to Apache sites-available
echo "[1/5] Copying Apache configuration to sites-available..."
sudo cp "$SOURCE_CONF" "$TARGET_CONF"

# 2. Add domain to /etc/hosts if missing
echo "[2/5] Updating /etc/hosts..."
if ! grep -q "$DOMAIN" /etc/hosts; then
    echo "127.0.0.1    $DOMAIN www.$DOMAIN" | sudo tee -a /etc/hosts > /dev/null
    echo "Added $DOMAIN and www.$DOMAIN to /etc/hosts"
else
    echo "$DOMAIN is already present in /etc/hosts"
fi

# 3. Enable Apache modules and the site
echo "[3/5] Enabling site and Apache modules..."
sudo a2enmod rewrite headers expires > /dev/null 2>&1 || true
sudo a2ensite "$VHOST_CONF"

# 4. Set directory permissions for web server user (www-data)
echo "[4/5] Adjusting directory permissions for web server..."
CURRENT_USER=$(id -un)
sudo chown -R "${CURRENT_USER}:www-data" /var/www/html/shopware6-zander
sudo chmod -R 775 /var/www/html/shopware6-zander

# 5. Start / Restart Services
echo "[5/5] Starting Apache and MySQL services..."
if command -v systemctl >/dev/null 2>&1 && systemctl is-system-running >/dev/null 2>&1; then
    sudo systemctl restart apache2
    sudo systemctl start mysql || true
else
    sudo service apache2 restart
    sudo service mysql start || true
fi

echo ""
echo "========================================="
echo " Setup Complete!"
echo "========================================="
echo "Open your browser to start the Shopware 6 Web Installer:"
echo "👉 http://${DOMAIN}/shopware-installer.phar.php"
echo ""
echo "Once the installation finishes through the browser, run:"
echo "👉 ./switch-to-public.sh"
echo "========================================="

