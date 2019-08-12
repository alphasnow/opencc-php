<?php

$file = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}
$autoload = require_once $file;

define('RESOURCES_PATH',realpath(__DIR__ . '/../resources'));
define('OUTPUTS_PATH',RESOURCES_PATH.'/outputs');

$possibleBinaries = [
    '/usr/bin/opencc',
    '/usr/local/bin/opencc',
    getenv('OPENCC_BINARY')?:'', // Fall back again to PATH
];
foreach ($possibleBinaries as $possibleBinary){
    if($possibleBinary && is_file($possibleBinary) && is_executable($possibleBinary)){
        try{
            \SleepCat\OpenCC\Command::create($possibleBinary);
            define('OPENCC_BINARY',$possibleBinary);
            break;
        }catch (Exception $e){
        }
    }
}

if(!defined('OPENCC_BINARY')){
    throw new RuntimeException(
        "Couldn't locate OpenCC.\n" .
        "Please check that OpenCC is installed and that it is located\n" .
        'in the global PATH variable, or that it is accessible in /usr/bin'
    );
}

$configPath = getenv('OPENCC_CONFIG')?:'';
if($configPath && !is_dir($configPath)){
    throw new RuntimeException(
        "OpenCC config folder doesn't exist:\n%s",
        $configPath
    );
}
define('OPENCC_CONFIG',$configPath);