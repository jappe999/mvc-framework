<?php

    namespace Controllers;

    use Core\Request as Request;
    use Core\Template as Template;

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

        private function getContent($path, $params = array())
        {
            $request = $this->request;

            $params = array_merge($params, compact('request'));

            $template = new Template($path);
            $file     = $template->render($params);

            return $file;
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
        public function view(string $name, array $params = array()): string
        {
            $path  = implode('/', array($this->viewsPath, $name));
            $file  = $this->getContent($path, $params);
            return (!empty($file)) ? $file : $this->error('404');
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
            $fileName = $code . '.view.php';
            $path = implode('/', array($this->errorPath, $fileName));
            return $this->getContent($path);
        }
    }
