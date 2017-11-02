<?php
/**
 * This file contains the core Router class.
 */

namespace Core;

use Controllers\Controller as Controller;

/**
 * This class can only be used by the root file. In this case public/index.php.
 * This class routes every request to the, in routes.php, defined methods and controllers.
 * The requested method will continue handle the request.
 */
class Router
{
    /**
     * Predefined routes in "/config/routes.json".
     *
     * @var array
     */
    private static $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Path to the routes.json file.
     *
     * @var string
     */
    private static $routesPath = CONFIG_PATH . 'routes.php';

    /**
     * Array with predefined regex pattern.
     * Corresponding to the key, the value will replace a parameter in the route.
     *
     * @var string
     */
    private static $regexPattern = '/\{([a-z0-9_]+)\}/i';

    private static $regexPatternStripped = '([a-z0-9_]+)';

    /**
     * Inserts a row into the GET array within self::$routes.
     *
     * @param string $uri
     * @param string $controller
     */
    public static function get(string $uri, string $controller) {
        self::$routes['GET'][trim($uri, '/')] = $controller;
    }

    /**
     * Inserts a row into the POST array within self::$routes.
     *
     * @param string $uri
     * @param string $controller
     */
    public static function post(string $uri, string $controller) {
        self::$routes['POST'][trim($uri, '/')] = $controller;
    }

    /**
     * Replaces all parameters with the corresponding regex character class.
     *
     * Replaces all parameters with the corresponding regex character class.
     * For example: {user_id} would be replaced with ([a-z0-9_]+).
     *
     * @param string $route
     *
     * @return string
     */
    private static function getRegexRoute(string $route): string
    {
        $route = trim($route, '/');
        return preg_replace(self::$regexPattern, self::$regexPatternStripped, $route);
    }

    /**
     * Returns the parameters from the path
     *
     * Replaces all parameters with the corresponding regex character class.
     * For example: {user_id} would be replaced with ([a-z0-9_]+).
     *
     * @param string $route
     * @param string $path
     *
     * @return array
     */
    private static function getParams($route, $path)
    {
        $params     = array();
        $routeParts = explode('/', $route);
        $pathParts  = explode('/', $path);

        foreach ($routeParts as $index => $routePart) {
            preg_match(self::$regexPattern, $routePart, $matches);
            if ($matches)
                $params[$matches[1]] = $pathParts[$index];
        }

        return $params;
    }

    /**
     * Execute specific controller.
     *
     * Include controller and execute the corresponding method with the given parameters.
     *
     * @param Request $request
     * @param string $route
     * @param array $info
     * @param string $path
     *
     * @return string
     */
    private static function executeController($route, $controller, $path)
    {
        // Include controller by name.
        $controllerParts = explode('@', $controller);
        $class           = $controllerParts[0];
        $method          = $controllerParts[1];

        $controllerName = 'Controllers\\' . $class;
        $controller     = new $controllerName;

        // Get parameters and call controller.
        $params = self::getParams($route, $path) ?? [];
        return call_user_func_array(
            [$controller, $method], $params
        );
    }

    /**
     * Load this class statically.
     *
     * Load this class satically with the routes path defined as constant or a custom one.
     *
     * @param string $routesPath
     * @return self
     */
    public static function load(string $routesPath = null): self
    {
        if (!empty($routesPath))
            self::$routesPath = $routesPath;

        require self::$routesPath;

        return new static;
    }

    /**
     * Get the requested path.
     *
     * Loop through the defined routes in "/config/routes.php".
     * If there is a match with the regex version of the route and the requested path,
     * the corresponding controller (also defined in "/config/routes.php") will be called.
     * If there is no match it will check if there is a file in "/public" that corresponds
     * to the path.
     * If all above fail, a 404 error will be raised.
     * The content will be echoed in this function.
     *
     */
    public static function handle()
    {
        // Set paths
        $path   = trim(Request::getPath(), '/');
        $public = PUBLIC_PATH . $path;
        $routes = self::$routes[Request::getMethod()];

        foreach ($routes as $route => $controller) {
            // Get the regex corresponding to the route.
            $regexRoute = self::getRegexRoute($route);
            if (preg_match("@^$regexRoute$@", $path)) {
                // Return a controller.
                echo (string) self::executeController($route, $controller, $path);

                // Exit complete method.
                return;
            } else if (file_exists($public)) {
                $fileType = explode('.', $public);
                $mimeType = mime_content_type($public);
                $mimeType = $fileType[count($fileType) - 1] == 'css' ? 'text/css' : $mimeType;

                header("Content-type:$mimeType");

                // Return an actual file.
                echo (string) file_get_contents($public);

                // Exit complete method.
                return;
            }
        }

        // Return 404 error.
        echo error('404');
    }
}
