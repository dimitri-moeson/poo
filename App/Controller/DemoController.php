<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 19:11
 */

namespace App\Controller;


use Core\Database\Query;
use Core\Database\QueryBuilder;

class DemoController extends AppController
{
    /**
     *
     */
    public function index()
    {
        echo Query::select('id','titre','contenu')->where('id = 1')->from('article','a') ;
    }
}