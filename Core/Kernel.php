<?php

    namespace Core;

    class Kernel
    {

        /**
         * Automatically include classes.
         *
         * Automatically include classes if they exist.
         *
         * @param string $class
         *
         * @return void
         */
        public function registerClass ($class)
        {
            $class = str_replace('\\', '/', $class);

            // If root is /
            $path = $_SERVER['DOCUMENT_ROOT'] . "/$class.php";

            // If root is /public
            if (!file_exists($path)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/../$class.php";
            }
            include_once $path;
        }

        /**
         * Recieves requests and passes them to the router.
         *
         * Recieves requests and passes them to the created router.
         *
         * @param Request $request
         *
         * @return string
         */
        public function handle (Request $request): string {
            $router = new Router();
            $page = $router->get($request);
            return $page;
        }
    }
