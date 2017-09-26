<?php

    /**
     * Controller class
     */
    class Controller
    {
        /**
         * Default path to views.
         *
         * @var string
         */
        private $viewsPath;

        /**
         * Default path to error views.
         *
         * @var string
         */
        private $errorPath;

        function __construct(Request $request)
        {
            $this->request   = $request;
            $this->viewsPath = __DIR__ . '/../views';
            $this->errorPath = $this->viewsPath . '/errors';
        }

        /**
         * Return view corresponding to the given name.
         *
         * Return the view corresponding to the given name
         * in the parameter $name.
         *
         * @param string $name
         *
         * @return string
         */
        public function view(string $name): string
        {
            $path = implode('/', array($this->viewsPath, $name));
            $file = file_get_contents($path);
            return ($file !== '') ? $file : $this->error('404');
        }

        /**
         * Return error view corresponding to the given error code.
         *
         * Return the error view corresponding to the given error code
         * in the parameter $code.
         *
         * @param string $code
         *
         * @return string
         */
        public function error(string $code): string
        {
            $fileName = $code . '.php';
            $path = implode('/', array($this->errorPath, $fileName));
            return file_get_contents($path);
        }
    }
