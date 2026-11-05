<?php
/**
 * Shopware 6 Temporary Installer Entry
 */

if (file_exists(__DIR__ . '/public/index.php')) {
    header('Location: /public/');
    exit;
}

if (file_exists(__DIR__ . '/shopware-installer.phar.php')) {
    header('Location: /shopware-installer.phar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopware 6 - Zander Environment</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px; display: flex; justify-content: center; align-items: center; min-height: 80vh; margin: 0; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); padding: 32px; max-width: 600px; width: 100%; }
        h1 { font-size: 24px; margin-top: 0; color: #0284c7; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 16px; }
        .btn:hover { background: #0369a1; }
        code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Shopware 6 - Local Environment Ready</h1>
        <p>Virtual host <code>shopware6-zander.local</code> is configured.</p>
        <p>Click below to launch the official Shopware 6 Web Installer:</p>
        <a href="/shopware-installer.phar.php" class="btn">Launch Shopware Installer &rarr;</a>
    </div>
</body>
</html>

