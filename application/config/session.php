<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'database' => array(
        'name' => 'session',
        'encrypted' => TRUE, // need a key in config/encrypt.php
        'lifetime' => DATE::HOUR, // 0 = expire when the browser close
        'group' => 'default',
        'table' => 'sessions',
        'columns' => array(
            'session_id'  => 'session_id',
            'last_active' => 'last_active',
            'contents'    => 'contents'
        ),
        'gc' => 500,
    ),
);
