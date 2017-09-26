<?php

    require_once __DIR__ . '/../Core/Kernel.php';
    require_once __DIR__ . '/../Core/Request.php';

    // Instanciate request class
    $request = new Request();

    // Get response from the kernel
    $response = handle($request);
    echo $response;
