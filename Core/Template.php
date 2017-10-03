<?php

namespace Core;

/**
 * A simple template engine
 */
class Template
{
    /**
     * Default path to views.
     *
     * @var string
     */
    private $viewsPath;

    function __construct(string $viewPath)
    {
        $this->viewsPath = __DIR__ . '/../views/';
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
        if (!empty($viewPath))
            $this->viewPath = $this->viewsPath . $viewPath;
    }

    private function removeNewlines(string $file): string
    {
        return preg_replace('/\n/', '', $file);
    }

    private function getExtend(string $file): array
    {
        $extendRegex = "/@extend\(\'(.*)\'\)/";
        $extend      = "";

        // Replace @extend() with file.
        preg_match($extendRegex, $file, $match);
        if ($match)
            $extend = file_get_contents($this->viewsPath . $match[1]);

        $file = preg_replace($extendRegex, '', $file);

        return array($file, $extend);
    }

    private function replaceYields(string $file, string $extend): string
    {
        $file = $this->removeNewlines($file);

        $yieldRegex   = "/@yield\(\'([a-z0-9_\/\-]*)\'\)/";
        $sectionRegex = "/@section\(\'(.*)\'\)(.*)@endsection/";

        preg_match_all($yieldRegex, $extend, $yields);
        preg_match_all($sectionRegex, $file, $sections);
        // var_dump($yields[1], $sections[1]);

        foreach ($yields[1] as $yield) {
            foreach ($sections[1] as $key => $section) {
                if ($yield === $section) {
                    $replaceRegex = "/@yield\(\'$yield\'\)/";
                    $replacement = $sections[2][$key];
                    $extend = preg_replace($replaceRegex, $replacement, $extend);
                }
            }
        }

        $extend = preg_replace($yieldRegex, '', $extend);

        return $extend;
    }

    /**
     * Render the view
     *
     * Render the view with the given parameters.
     *
     * @param array $params
     *
     * @return string
     */
    function render(array $params = array()): string
    {
        $file = file_get_contents($this->viewPath);

        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                ${$key} = $val;
            }
        }

        list($file, $extend) = $this->getExtend($file);

        if (!empty($extend)) {
            $file = $this->replaceYields($file, $extend);
        }

        return $file;
    }
}
