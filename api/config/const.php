<?php
return [
    'email_dns_regex' => '/^(?:[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\.[^@][a-zA-Z]{1,4})?$/',
    'password_regex' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&\/\\\"{}|;<>' .
        '[\]`+~=_,^()-.\:\'])[A-Za-z\d@$!%*#?&\/\\\"{}|;<>[\]`+~=_,^()-.\:\']{8,20}$/i',
    'date_format' => 'Y-m-d',
    'datetime_format' => DateTime::ATOM,
    'date_format_filename' => 'Ymd',
    'date_format_en_slash' => 'Y/m/d',
    'date_format_template_mail' => 'Y/m/d H:i:s',
    'zoom_start_time_format' => "Y-m-d\TH:i:s",
    'date_format_timestamp' => 'Y-m-d H:i:s',
    'date_format_not_include_second' => 'Y/m/d H:i',
    'format_file_upload' => [
        'csv',
        'xlsx'
    ],
    'format_encoding' => [
        'utf_8' => 'UTF-8',
        'ISO-8859-1',
        'SJIS',
    ],
    'refresh_token_lifetime' => env('REFRESH_TOKEN_LIFETIME', 180),
    'confirmation_token_lifetime' => env('CONFIRMATION_TOKEN_LIFETIME', 10080),
];