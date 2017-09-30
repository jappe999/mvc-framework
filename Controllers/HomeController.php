<?php

    namespace Controllers;

    /**
     * HomeController
     */
    class HomeController extends Controller
    {
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
