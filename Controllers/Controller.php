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

        private function getContent($path, $params)
        {
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    ${$key} = $value;
                }
            }


            ob_start();
            include $path;
            $file = ob_get_contents();
            ob_end_clean();

            // TODO: Fix template engine
            $template = new Template('home.view.php');
            $file     = $template->render();

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
        public function view(string $name, array $params = NULL): string
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
            $fileName = $code . '.template.php';
            $path = implode('/', array($this->errorPath, $fileName));
            return file_get_contents($path);
        }
    }
