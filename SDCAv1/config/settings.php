<?php

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');

// Timezone
date_default_timezone_set('Europe/Madrid');

defined('__ROOT__') or define('__ROOT__', dirname(dirname(__FILE__)));

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';
$settings['determineRouteBeforeAppMiddleware']= true;

// Error Handling Middleware settings
$settings['error_handler_middleware'] = [

    // Should be set to false in production
    'display_error_details' => true,

    // Parameter is passed to the default ErrorHandler
    // View in rendered output by enabling the "displayErrorDetails" setting.
    // For the console and unit tests we also disable it
    'log_errors' => true,

    // Display error details in error log
    'log_error_details' => true,
];

// Database settings
$settings['db'] = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'username' => 'usuarioAut',
    'database' => 'contabilidadautonomos',
    'password' => '123456',
    'charset' => 'utf8',
    'collation' => 'utf8_spanish2_ci',
    'flags' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
    ],
];

$settings['jwt'] = [
    
    // The issuer name
    'issuer' => 'www.gestionproyectos.com',
    
    // Max lifetime in seconds
    'lifetime' => 14400,
    
    // The private key
    'private_key' => '-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA80R11XcQiE0TOL0qXsMsU9VutwyrozyRpPf8emRzoNxWMf6X
0pjFTy9dKV3d+mZQKyYkWmf+L4VEYnt3xd4387IW9haT1E5ZUuSuJo2DrC5b7P05
YnW48mbQmGBXcfZDCpk1XnbTuwqC2g8yxP9G4CYIwbedevDVXdeV/7pwPOafRfnv
6a78qtyGLz3UVuALVIIHOCBahBiZMvjo+wJy++3Jf1GeuRGUwFJxxwpZZerJBcjp
C7V+7LksTsEEdfIUq4MnLikmns4pwfSufEjlpGBf6ttuMkvrmkRtvkBIARnTYxAI
dTptZ6Xn/BbFE7ySsEVsBKI8qAQ1ntWecw5ARwIDAQABAoIBAQCTW+4RD93I4v94
yEXpP0sTwV8erSMvb5o+FyYusbjFQZNJF5K6NGaZL/S8AStc6is36BPvW71C5fRl
v6rvBkxYZ5G3IjgMTCWZrAK+Qcz4OODgbwga13rgA13qX7m4w9cajXIhtdNOIvDe
zIQRhx1U3FVDm0le1pI5FUW8siAZEuAillcuuHwA9Anmpf24e0KvXmzD17KKNtzB
qhRNrHcMoNf0u2osMqzBbZgijueGW/cKOrtnL9iLs42okkUm27wmEcoP6LoZdFYY
sFiMzvrDXgIPCrtX9xDq9H7snoUrtlF5fC1UiO4mbL91F3lfB0jxif660Z4Br0pU
jNWy8ZhZAoGBAPsPNQ94NiDXWz3Q8k/Z9umYQLYNq9Ouu6urrJPMgt34P1rHKY6d
UtwVXcW1s1mBT2gYPC4THYf+5vmA52zyjvdNRKuCPWWqpi9ehrqDPWxSMtZjVGTG
iOgygteGGks/9UeXdcu1fp9YgQPbe5CtBwqyCPWzk90k73QT8vaRl6aVAoGBAPgN
/5ePxp+Mi5AFGYUNZyQuh1wtnlEuqoD4e9bX/i5q8Tqf3M2EV1An6BS7lkw6m5a+
/isx/YYsSY1IVW5dRvDpzAioWYvXruCvNbAnFx9p1shP2i59MIKQSflQJZ1affJ9
RkWx5lxbQbd/h2yQivvptf7sYs+rVlmvAKPLNSBrAoGAC0BGN3hCrLwZ+a44fb2d
5CAPnlkEf0sQXAQoisMahkoOxMmyIhMI2JYYiri23eb+oQKdB8+Rfju9LCdLt8Uz
BQAweKMwjzLTmcHZtdgV05nnb1YqPQ3sqsNAu9nlqcOnAVtXSxbKIuf7eCnI4tXu
P9t0rVYr5Tg3hvMx6zzhF/UCgYEA7Nrg/WWJ8PsQoFpePc/IdqH/GiNtEnOO10tt
ZH4l9UikQqg2+/6sKJuSXYmpP+yOeGiy5af8Psu3B7LT8Ggcvxlfe7zUlFVEZMKo
byVETPiQ4ABvgwiC84i3OdovsIqhzJOWMCJopjN1ErQxKZbzPwuwflRVHZqq/0O9
RqlYdzUCgYAVY0c2qwnuF2nduA+uX1ejCgwmldVZobUiQGlLisoKkN+eo44UQlR8
eiiMvxpUpNir4Jea1LBEVUt8K+3vi5ID/8tk1Azrsyh0rN3ShLWOw0X7bulbz4kn
hgiF5Vys/4W1BayU3ybkrY9tnbzMQxtScVKUu95nnaB/e3VrcFPkUg==
-----END RSA PRIVATE KEY-----
',
    
    'public_key' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA80R11XcQiE0TOL0qXsMs
U9VutwyrozyRpPf8emRzoNxWMf6X0pjFTy9dKV3d+mZQKyYkWmf+L4VEYnt3xd43
87IW9haT1E5ZUuSuJo2DrC5b7P05YnW48mbQmGBXcfZDCpk1XnbTuwqC2g8yxP9G
4CYIwbedevDVXdeV/7pwPOafRfnv6a78qtyGLz3UVuALVIIHOCBahBiZMvjo+wJy
++3Jf1GeuRGUwFJxxwpZZerJBcjpC7V+7LksTsEEdfIUq4MnLikmns4pwfSufEjl
pGBf6ttuMkvrmkRtvkBIARnTYxAIdTptZ6Xn/BbFE7ySsEVsBKI8qAQ1ntWecw5A
RwIDAQAB
-----END PUBLIC KEY-----',
    
];
return $settings;