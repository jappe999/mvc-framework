<?php

    /**
     * Router class
     */
    class Router
    {
        /**
         * Predefined routes in "/config/routes.json".
         *
         * @var array
         */
        private $routes;

        /**
         * Path to the routes.json file.
         *
         * @var string
         */
        private static $routesPath = __DIR__ . '/../config/routes.json';

        /**
         * Array with predefined regex patterns.
         * Corresponding to the key, the value will replace a parameter in the route.
         *
         * @var array
         */
        private static $regexPatterns = array(
            'number' => '\d+',
            'string' => '\w',
        );

        /**
         * Gets the routes from the path given in self::routesPath and puts them in $this->routes.
         */
        public function __construct()
        {
            if (file_exists(self::$routesPath)) {
                $routesPath = file_get_contents(self::$routesPath);
                $this->routes = json_decode($routesPath, true);
            }
        }

        /**
         * Replaces all parameters with the corresponding regex character class.
         *
         * Replaces all parameters with the corresponding regex character class.
         * For example: :id would be replaced by \d+. This is defined in "/config/routes.json".
         * Strings will be replaced with \w+ and number with \d+.
         *
         * @param string $route
         * @param array $info
         *
         * @return string
         */
        private function getRegexRoute($route, $info): string
        {
            if (isset($info['params'])) {
                foreach ($info['params'] as $name => $type) {
                    $route = str_replace(
                        ':' . $name, self::$regexPatterns, $route
                    );
                }
            }

            return $route;
        }

        /**
         * Returns the parameters from the path
         *
         * Replaces all parameters with the corresponding regex character class.
         * For example: :id would be replaced by \d+. This is defined in "/config/routes.json".
         * Strings will be replaced with \w+ and number with \d+.
         *
         * @param string $route
         * @param string $path
         *
         * @return array
         */
        private function getParams($route, $path)
        {
            $params     = array();
            $routeParts = explode('/', $route);
            $pathParts  = explode('/', $path);

            foreach ($routeParts as $index => $routePart) {
                // If first char is a :
                if (strpos($routePart, ':') === 0) {
                    $paramName = substr($routePart, 1);
                    $params[$paramName] = $pathParts[$index];
                }
            }
        }

        /**
         * Execute specific controller.
         *
         * Include controller and execute the corresponding method with the given paramters.
         *
         * @param Request $request
         * @param string $route
         * @param array $info
         * @param string $path
         *
         * @return string
         */
        private function executeController($request, $route, $info, $path)
        {
            // Include controller by name.
            $controllerName = $info['controller'] . 'Controller';
            include_once __DIR__ . "/../Controllers/$controllerName.php";
            $controller = new $controllerName($request);

            // Get parameters and call controller.
            $params = $this->getParams($route, $path) ?? array();
            return call_user_func_array(
                [$controller, $info['method']], $params
            );
        }

        /**
         * Get the requested path.
         *
         * Loop through the defined routes in "/config/routes.json".
         * If there is a match with the regex version of the route and the requested path,
         * the corresponding controller (also defined in "/config/routes.json") will be called.
         * If there is no match it will check if there is a file in "/public" that corresponds
         * to the path.
         * If all above things fail a 404 error will be raised.
         *
         * @param Request $request
         *
         * @return string
         */
        public function get(Request $request): string
        {
            // Set paths
            $path = $request->getPath();
            $public = __DIR__ . "/../public/$path";

            foreach ($this->routes as $route => $info) {
                // Get the regex corresponding to the route.
                $regexRoute = $this->getRegexRoute($route, $info);
                if (preg_match("@^$regexRoute$@", $path)) {
                    // Return a controller.
                    return (string) $this->executeController($request, $route, $info, $path);
                } else if (file_exists($public)) {
                    // Return an actual file.
                    return (string) file_get_contents($public);
                }
            }

            // Return 404 error.
            include_once __DIR__ . '/../Controllers/Controller.php';
            $controller = new Controller($request);
            return $controller->error('404');
        }
    }
