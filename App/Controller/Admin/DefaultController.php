<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 19:11
 */

namespace App\Controller\Admin;

use App;
use Core\Render\Render;

class DefaultController extends AppController
{
    /**
     * DefaultController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param null $slug
     */
    public function index()
    {
        Render::getInstance()->setView("Admin/Index");
    }
}