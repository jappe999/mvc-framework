<?php
/**
 * This file contains the Template class.
 */

namespace Core;

/**
 * A simple template engine.
 * It contains unnecessary much code, but it'll have to do.
 */
class Template
{
    /**
     * Path to views.
     *
     * @var string
     */
    private $viewPath;

    /**
     * Constructing
     */
    function __construct(string $viewPath)
    {
        $this->setPath($viewPath);
    }

    /**
     * Set parameters for the template to render.
     *
     * Give the template engine the variables to render the view.
     *
     * @param string $viewPath
     */
    public function setPath(string $viewPath)
    {
        if (!empty($viewPath)) {
            if (strpos($viewPath, VIEWS_PATH) === false) {
                $this->viewPath = VIEWS_PATH . $viewPath;
            } else {
                $this->viewPath = $viewPath;
            }
        }
    }

    /**
     * Define parameters in the class dynamically.
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        // Set parameters
        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Just removes new line chars.
     *
     * @param string $file
     * @return string
     */
    private function removeNewlines(string $file): string
    {
        return preg_replace('/\n/', '', $file);
    }

    /**
     * Get the extend of a file. Aka the base file of the view.
     *
     * Get the file contents of the extend defined in the view and
     * remove any @extend()'s from the view.
     *
     * @param string $file
     * @return array Stripped file and extend file contents.
     */
    private function getExtend(string $file): array
    {
        $extendRegex = "/@extend\(\'(.*)\'\)/";
        $extend      = "";

        // Replace @extend() with file contents.
        preg_match($extendRegex, $file, $match);
        if ($match)
            $extend = file_get_contents(VIEWS_PATH . $match[1]);

        $file = preg_replace($extendRegex, '', $file);

        return array($file, $extend);
    }

    /**
     * Replace @yield with the content of @section
     *
     * Replace @yield statements with the content the corressponding @section statement
     *
     * @param string $file
     * @param string $extend
     * @return string
     */
    private function renderYields(string $file, string $extend): string
    {
        $file = $this->removeNewlines($file);

        $yieldRegex   = "/@yield\(\'([a-z0-9_\/\-]*)\'\)/";
        $sectionRegex = "/@section\(\'([a-zA-Z0-9\_\-\/\\\.]+)\'\)(.*)@endsection/";

        preg_match_all($yieldRegex, $extend, $yields);
        preg_match_all($sectionRegex, $file, $sections);

        // Replace @yield with corressponding @section
        foreach ($yields[1] as $yield) {
            foreach ($sections[1] as $key => $section) {
                if ($yield === $section) {
                    // Create a custom regex
                    $replaceRegex = "/@yield\(\'$yield\'\)/";
                    $replacement = $sections[2][$key];
                    $extend = preg_replace($replaceRegex, $replacement, $extend);
                }
            }
        }

        // Remove unused @yield statements
        $extend = preg_replace($yieldRegex, '', $extend);

        return $extend;
    }

    /**
     * Dumb check for variable checking :/
     *
     * @param string $tag
     * @return bool
     */
    private function isVariable(string $tag): bool
    {
        return !preg_match("/(->|\(.*\))/", $tag);
    }

    /**
     * Get the output of the called function in the template.
     *
     * @param string $tag
     * @param array $params
     * @return array|string|int
     */
    private function getFunction(string $tag, array $params)
    {
        $splittedTag = preg_split("/(->)/", $tag);

        $response = '';
        foreach ($splittedTag as $key => $value) {
            // Remove empty items.
            if (empty($value)) {
                unset($splittedTag[$key]);
                continue;
            }

            // Remove dollar sign.
            $value = preg_replace('/^\$/', '', $value);

            if ($value == 'this') {
                // If variable starts with $this.
                $response = $this;
            } else if (preg_match('/\([\"\']?(.*(?![\)]))[\"\']?\)$/', $value, $matches)) {
                // Match a function with arguments: function ('arguments')

                // Get arguments and strip the arguments from $value.
                $arguments = explode(',', $matches[1]);
                $value     = preg_replace('/\((.*)\)$/', '', $value);

                // Call the function with or without $response.
                $callable = ($response != '') ? array($response, $value) : $value;
                $response = call_user_func_array($callable, $arguments);
            } else if (preg_match('/\(\)/', $value)) {
                // Match a function without arguments.

                // Remove () from $value.
                $value    = preg_replace('/\(\)$/', '', $value);

                // Call the function.
                $response = $response->{$value}();
            } else if (empty($response)) {
                // If the first variable isn't set yet and all above conditions don't match.
                $response = $this->{$value};
            } else {
                // Call next variable in line.
                $response = $response->{$value};
            }
        }

        return $response;
    }

    /**
     * Render all variables and functions.
     *
     * @param string $file
     * @param array $params
     * @return string
     */
    private function renderTags(string $file, $params): string
    {
        $this->setParams($params);

        $regex = "/{{\s*([\$\_\-\>\<a-zA-Z0-9\(\)\[\]\'\"]*)\s*}}/";
        // $regex = "/{{\s*(.*)\s*}}/";
        preg_match_all($regex, $file, $matches);
        // var_dump($matches);
        $tags = $matches[1];

        foreach ($tags as $tag) {
            if ($this->isVariable($tag))
                $replacement = ${$tag};
            else
                $replacement = $this->getFunction($tag, $params);

            $escapedTag = preg_quote($tag);
            $regex      = "/{{\s*($escapedTag)\s*}}/";
            $file       = preg_replace($regex, $replacement, $file);
        }

        return $file;
    }

    /**
     * Render the view.
     *
     * Render the view with the given parameters.
     *
     * @param array $params
     * @return string
     */
    function render(array $params = array()): string
    {
        $file = file_get_contents($this->viewPath);

        // Get the extend of the file
        list($file, $extend) = $this->getExtend($file);

        if (!empty($extend))
            $file = $this->renderYields($file, $extend);

        $file = $this->renderTags($file, $params);

        return $file;
    }
}
