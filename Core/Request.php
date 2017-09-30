<?php

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
         * Domain that is called.
         *
         * @var string
         */
        private $domain;

        /**
         * Path that is requested.
         *
         * @var string
         */
        private $path;

        /**
         * Method the page is called with.
         *
         * @var string
         */
        private $method;

        /**
         * Parameters that are given.
         *
         * @var string
         */
        private $params;

        /**
         * Cookies that are given.
         *
         * @var string
         */
        private $cookies;

        /**
         * Set variables.
         *
         * Set predefined variables (see above).
         */
        public function __construct()
        {
            $this->domain  = $_SERVER['HTTP_HOST'];
            $this->path    = $_SERVER['REQUEST_URI'];
            $this->method  = $_SERVER['REQUEST_METHOD'];

            $this->params  = new FilteredMap($_REQUEST);
            $this->cookies = new FilteredMap($_COOKIE);
        }

        /**
         * Retrieve the url that is called.
         *
         * Retrieve a string with the complete url that is called.
         *
         * @return string
         */
        public function getUrl(): string
        {
            return $this->domain . $this->path;
        }

        /**
         * Retrieve the domain that is called.
         *
         * Retrieve a string with the domain that is called.
         *
         * @return string
         */
        public function getDomain(): string
        {
            return $this->domain;
        }

        /**
         * Retrieve the path that is called.
         *
         * Retrieve a string with the path that is called.
         *
         * @return string
         */
        public function getPath(): string
        {
            return $this->path;
        }

        /**
         * Retrieve the method that the url is called with.
         *
         * Retrieve the method that the url is called with (GET or POST).
         *
         * @return string
         */
        public function getMethod(): string
        {
            return $this->method;
        }

        /**
         * Retrieve the parameters that are given with the request.
         *
         * Retrieve a FilteredMap instance with the parameters given with the request.
         *
         * @return FilteredMap
         */
        public function getParams(): FilteredMap
        {
            return $this->params;
        }

        /**
         * Retrieve the cookies that are given with the request.
         *
         * Retrieve a FilteredMap instance with the cookies given with the request.
         *
         * @return FilteredMap
         */
        public function getCookies(): FilteredMap
        {
            return $this->cookies;
        }

        /**
         * Check if the path is requested with the POST method or not.
         *
         * Check if the path is requested with the POST method or not.
         *
         * @return boolean
         */
        public function isPost(): bool
        {
            return $this->method === self::POST;
        }

        /**
         * Check if the path is requested with the GET method or not.
         *
         * Check if the path is requested with the GET method or not.
         *
         * @return boolean
         */
        public function isGet(): bool
        {
            return $this->method === self::GET;
        }
    }
