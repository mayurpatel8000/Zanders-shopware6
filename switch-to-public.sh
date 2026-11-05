#!/bin/bash
set -e

SITE_CONF="/etc/apache2/sites-available/shopware6-zander.local.conf"
SRC_CONF="/var/www/html/shopware6-zander/shopware6-zander.local-public.conf"

echo "Switching Virtual Host DocumentRoot to /public..."
sudo cp "$SRC_CONF" "$SITE_CONF"
if command -v systemctl >/dev/null 2>&1 && systemctl is-system-running >/dev/null 2>&1; then
    sudo systemctl reload apache2
else
    sudo service apache2 reload
fi
echo "Successfully updated Virtual Host DocumentRoot to /var/www/html/shopware6-zander/public!"

