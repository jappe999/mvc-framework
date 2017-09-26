<?php

    require_once 'Controller.php';

    /**
     * HomeController
     */
    class HomeController extends Controller
    {

        function __construct(Request $request)
        {
            parent::__construct($request);
        }

        /**
         * Get the home page.
         *
         * Get the home page.
         *
         * @return string
         */
        public function get(): string
        {
            return $this->view('home.php');
        }
    }
