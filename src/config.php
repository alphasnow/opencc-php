<?php

return [
    // Window
//    'binary_path'=>'D:/OpenCC/build/bin/opencc.exe',
//    'config_path'=>'D:/OpenCC/build/share/opencc',

    // Ubuntu
//    'binary_path'=>'/usr/bin/opencc',
//    'config_path'=>'/usr/share/opencc',

    'binary_path' => env('OPENCC_BINARY', '/usr/bin/opencc'),
    'config_path' => env('OPENCC_CONFIG', '/usr/share/opencc'),

    'process' => [
        'env' => null,
        'timeout' => 60,
    ],
];
