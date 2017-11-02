<?php
/**
 * Helper functions.
 * These functions can be used in the global scope of the application.
 */

use Core\Request as Request;
use Core\Template as Template;

/**
 * Get the rendered content of a view.
 *
 * @param string $name
 * @param array  $params
 *
 * @return string
 */
function getContent(string $name, $params = array()): string
{
    $domain  = Request::getDomain();
    $path    = Request::getPath();
    $params  = array_merge($params, compact('domain', 'path'));

    // Render view
    $template = new Template($name);
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
 * @param array  $params
 *
 * @return string
 */
function view(string $name, array $params = array()): string
{
    $file  = getContent($name, $params);
    return (!empty($file)) ? $file : error('404');
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
function error(string $code): string
{
    header('HTTP/1.0 404 Not Found');
    $fileName = $code . '.view.php';
    $path = ERROR_VIEWS_PATH . $fileName;
    return getContent($path);
}

/**
 * Redirects to $path
 *
 * @param string $path
 */
function redirect(string $path)
{
    header('Location: ' . $path);
}

/**
 * Generates a random integer.
 *
 * @return int
 */
function randomId(): int
{
    // Don't ask why I chose this number.
    return random_int(0, 4294967295);
}

/**
 * Hashed a string following the blowfish principle.
 *
 * https://gist.github.com/dzuelke/972386
 *
 * @param string $string
 *
 * @return string
 */
function encrypt(string $string): string
{
    $blowfishSalt = bin2hex(openssl_random_pseudo_bytes(22));
    $hash = crypt($string, "$2y$12$" . $blowfishSalt);

    return $hash;
}

/**
 * Verify a string with a hash retrieved from the encrypt function.
 *
 * @param string $hash
 * @param string $string
 *
 * @return bool
 */
function verifyHash(string $hash, string $string): bool
{
    return ($hash == crypt($string, $hash));
}
