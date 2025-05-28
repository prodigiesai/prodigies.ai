<?php
/**
   AbanteCart, Ideal OpenSource Ecommerce Solution
   https://www.AbanteCart.com
   Copyright © 2011-2024 Belavier Commerce LLC

   Released under the Open Software License (OSL 3.0)
*/

const SERVER_NAME = 'prodigies.ai';
// Admin Section Configuration. You can change this value to any name. Will use ?s=name to access the admin
const ADMIN_PATH = 'cpanel';

// Database Configuration
const DB_DRIVER = 'amysqli';
const DB_HOSTNAME = 'localhost';
const DB_USERNAME = 'vivhjgte_aban772';
const DB_PASSWORD = '[tSF0525p)';
const DB_DATABASE = 'vivhjgte_aban772';
const DB_PREFIX = 'ab6t_';

const CACHE_DRIVER = 'file';
// Unique AbanteCart store ID
const UNIQUE_ID = 'ce94af28f50536a8bf482cff3c4eb910';
// Encryption key for protecting sensitive information. NOTE: Change of this key will cause a loss of all existing encrypted information!
const ENCRYPTION_KEY = 'KP7514';

// details about allowed DSN settings  https://symfony.com/doc/6.0/mailer.html#transport-setup
/*
const MAILER = [
    //'dsn' => null,
    // OR
    'protocol' => 'smtp', // or ses+smtp, gmail+smtp, mandrill+smtp, mailgun+smtp, mailjet+smtp, postmark+smtp, sendgrid+smtp, sendinblue+smtp, ohmysmtp+smtp
    //we use "username" also as ID, KEY, API_TOKEN, ACCESS_KEY
    'username' => 'merchant@yourdomain.com',
    'password' => '****super-secret-password****',
    'host'     => 'your-hostname',
    'port'     => 465 //or 587 etc
];
*/