<?php

return [
    /*
    // Window
    'binary_path'=>'D:/OpenCC/build/bin/opencc.exe',
    'config_path'=>'D:/OpenCC/build/share/opencc',

    // Ubuntu
    'binary_path'=>'/usr/bin/opencc',
    'config_path'=>'/usr/share/opencc',
    */

    'binary_path' => env('OPENCC_BINARY', '/usr/bin/opencc'),
    'config_path' => env('OPENCC_CONFIG', ''),
    'config_extension' => env('OPENCC_CONFIG_EXTENSION', '.json'), // .json/.ini

    'process' => [
        'cwd' => null,
        'env' => null,
        'timeout' => 10,
    ],
];
