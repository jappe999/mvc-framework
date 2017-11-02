<?php

namespace Models;

use Core\Database as DB;

class DummyModel
{
    public function getDummyData()
    {
        return array(
            'message' => 'This data is retrieved with POST headers.',
            'your_key' => 'your_value',
            'some_key' => 'some_value',
        );
    }
}
