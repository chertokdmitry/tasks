<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.19
 * Time: 21:26
 */

namespace Models;

class Config
{
    public function index()
    {
        return ['mysql:host=78.108.95.175;dbname=tasks_loc',
            'newuser', 'Docker1703'];
    }
}
