<?php

    require_once '../Core/Kernel.php';

    // Get response from the kernel
    $kernel = new Core\Kernel();

    spl_autoload_register(function ($class) {
        global $kernel;
        $kernel->registerClass($class);
    });

    // Instanciate request class
    $request = new Core\Request();

    echo $kernel->handle($request);
