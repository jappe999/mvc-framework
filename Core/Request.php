<?php
/**
 * This file contains the core Request class.
 */

namespace Core;

use Core\FilteredMap as FilteredMap;

/**
 * Class to easily cut requests into pieces.
 *
 * Class where others can easily extract request parameters from.
 */
class Request
{
    const GET  = 'GET';
    const POST = 'POST';

    /**
     * Trim the path and remove parameters.
     *
     * @param  string $path
     * @return string
     */
    private static function trimPath(string $path): string
    {
        // Check if request has any GET parameters.
        if (self::isGet() && self::getParams()->length() > 0) {
            $paramsPos   = strpos($path, '?');
            $trimmedPath = substr($path, 0, $paramsPos);
        } else {
            $trimmedPath = $path;
        }

        return $trimmedPath;
    }

    /**
     * Retrieve the url that is called.
     *
     * Retrieve a string with the complete url that is called.
     *
     * @return string
     */
    public static function getUrl(): string
    {
        return self::getDomain() . self::getPath();
    }

    /**
     * Retrieve the domain that is called.
     *
     * Retrieve a string with the domain that is called.
     *
     * @return string
     */
    public static function getDomain(): string
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Retrieve the path that is called.
     *
     * Retrieve a string with the path that is called.
     *
     * @return string
     */
    public static function getPath(): string
    {
        return self::trimPath($_SERVER['REQUEST_URI']);
    }

    /**
     * Retrieve the method that the url is called with.
     *
     * Retrieve the method that the url is called with (GET or POST).
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Retrieve the parameters that are given with the request.
     *
     * Retrieve a FilteredMap instance with the parameters given with the request.
     *
     * @return FilteredMap
     */
    public static function getParams(): FilteredMap
    {
        if (!empty($_REQUEST))
            return new FilteredMap($_REQUEST);

        return new FilteredMap(
            json_decode(file_get_contents("php://input"), true)
        );
    }

    /**
     * Retrieve the cookies that are given with the request.
     *
     * Retrieve a FilteredMap instance with the cookies given with the request.
     *
     * @return FilteredMap
     */
    public static function getCookies(): FilteredMap
    {
        return new FilteredMap($_COOKIE);
    }

    /**
     * Check if the path is requested with the POST method or not.
     *
     * Check if the path is requested with the POST method or not.
     *
     * @return boolean
     */
    public static function isPost(): bool
    {
        return self::getMethod() === self::POST;
    }

    /**
     * Check if the path is requested with the GET method or not.
     *
     * Check if the path is requested with the GET method or not.
     *
     * @return boolean
     */
    public static function isGet(): bool
    {
        return self::getMethod() === self::GET;
    }
}
