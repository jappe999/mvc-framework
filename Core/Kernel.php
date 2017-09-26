<?php

    require_once 'Router.php';

    /**
     * Recieves requests and passes them to the router.
     *
     * Recieves requests and passes them to the created router.
     *
     * @param Request $request
     *
     * @return string
     */
    function handle (Request $request): string {
        $router = new Router();
        $page = $router->get($request);
        return $page;
    }
