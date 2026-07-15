<?php

/**
 * Configuration globale de l'application NDIGITMARKET.
 */

// Environnement
$GLOBALS['APP_DEBUG'] = true;
$GLOBALS['APP_NAME']  = 'NDIGITMARKET';

define('BASE_URL', '/back-end'); // Timezone

// $GLOBALS['BASE_URL']  = '/ndigitmarket';
date_default_timezone_set('Africa/Dakar');

// Sécurité de session (à activer en production HTTPS)
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
// ini_set('session.cookie_secure', '1');
