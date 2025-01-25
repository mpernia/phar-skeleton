<?php

    $rootDir = dirname(__DIR__, 1);
    $envFile = sprintf('%s/.env', $rootDir);
    $env = [];

    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $env[trim($key)] = trim($value);
            }
        }
    }

    $buildDir = $env['BUILD_DIR'] ?? 'build';
    $pharName = $env['PHAR_NAME'] ?? 'app.phar';
    $buildPath = $rootDir . '/' . $buildDir;
    $pharFile = $buildPath . '/' . $pharName;

    if (!is_dir($buildPath)) {
        if (!mkdir($buildPath, 0777, true)) {
            die("Error: Could not create directory $buildPath\n");
        }
    }

    if (file_exists($pharFile)) {
        unlink($pharFile);
    }

    try {
        $phar = new Phar($pharFile);
        $phar->startBuffering();
        
        $srcDir = $rootDir . '/src';
        if (!is_dir($srcDir)) {
            die("Error: The src directory does not exist in $srcDir\n");
        }

        $baseDir = $rootDir;
        $dirIterator = new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($dirIterator);
        
        echo "Adding files to phar:\n";
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $localPath = str_replace($baseDir . '/', '', $file->getPathname());
                echo "- $localPath\n";
                $phar->addFile($file->getPathname(), $localPath);
            }
        }

        $autoloaderContent = <<<'EOT'
<?php
    function debug($message) 
    {
        file_put_contents('php://stderr', "[DEBUG] $message\n");
    }

    spl_autoload_register(function ($class) 
    {
        $prefix = 'App\\';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relative_class = substr($class, $len);
        $file = 'phar://' . Phar::running(false) . '/src/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
            if (class_exists($class, false)) {
                return true;
            }
            return false;
        }
        return false;
    });

    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        debug("Error $errno: $errstr in $errfile:$errline");
        return false;
    });

    set_exception_handler(function($e) {
        debug("Uncaught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    });
EOT;
        
        $envLoaderContent = <<<'EOT'
<?php
    function loadEnv($file) {
        if (!file_exists($file)) {
            return;
        }
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                if (!empty($key)) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
    }
    $envFile = getcwd() . '/.env';
    if (!file_exists($envFile)) {
        $envFile = dirname(getcwd()) . '/.env';
    }
    if (!file_exists($envFile)) {
        $envFile = dirname(Phar::running(false)) . '/.env';
    }
    loadEnv($envFile);
EOT;
        
        $indexContent = file_get_contents($srcDir . '/index.php');
        $indexContent = preg_replace('/require_once.*autoload\.php.*;\n*/m', '', $indexContent);
        $phar->addFromString('src/index.php', $indexContent);
        $phar->addFromString('autoload.php', $autoloaderContent);
        $phar->addFromString('env.php', $envLoaderContent);
            
        $stub = <<<EOT
#!/usr/bin/env php
<?php
    Phar::mapPhar('$pharName');
    require 'phar://' . __FILE__ . '/env.php';
    require 'phar://' . __FILE__ . '/autoload.php';
    require 'phar://' . __FILE__ . '/src/index.php';
    __HALT_COMPILER();
EOT;
        
        $phar->setStub($stub);
        $phar->compressFiles(Phar::GZ);
        $phar->stopBuffering();
        chmod($pharFile, 0755);
        echo "\nPHAR file successfully created at: $pharFile\n";
        echo "File size: " . number_format(filesize($pharFile)) . " bytes\n";
        
    }catch (Exception $e) {
        die("Error creating PHAR file: " . $e->getMessage() . "\n");
    }
