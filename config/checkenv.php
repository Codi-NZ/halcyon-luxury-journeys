<?php

/**
 * Missing env keys notification
 */
$checkEnvKeys = function ($requiredEnvKeys) {
    if (php_sapi_name() == 'cli') return;
    $missingEnvKeys = array_map(function ($envKey) {
        return empty(getEnv($envKey)) ? "👉 Add a value for <code>${envKey}</code> to your .env" : null;
    }, $requiredEnvKeys);
    if (sizeOf(array_filter($missingEnvKeys)) === 0) return;
    echo "<div style='font-size:120%;font-family:arial'>" . join('<br>', array_filter($missingEnvKeys)) . "</div>";
    exit();
};

$checkEnvKeys([
    'ENVIRONMENT',
    'SECURITY_KEY',
    'DB_SERVER',
    'DB_DATABASE',
]);
