<?php

namespace Controllers;

use Core\Request as Request;
use Models\DummyModel as DummyModel;
use Core\Database as DB;

/**
 * HomeController
 */
class TestController extends Controller
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
        return view('home.view.php');
    }

    /**
     * Dummy post contoller method.
     *
     * @return string
     */
    public function post(): string
    {
        $data = DummyModel::getDummyData();
        return json_encode($data);
    }

    /**
     * Get dynamic content with curly brackets.
     *
     * {something} will be given as parameter to the method
     * defined in config/routes.php.
     */
    public function parameterExample($randomId): string
    {
        return "The random id you gave was: " . $randomId;
    }

    /**
     * Example of how to use the database class.
     *
     * The data base class returns
     * a PDO instance you can work with.
     *
     * @return string
     */
    public function makeDBConnection(): string
    {
        $conn = DB::connect();
        return var_dump($conn);
    }
}
