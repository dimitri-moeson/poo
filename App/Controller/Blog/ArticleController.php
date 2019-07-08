<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 09:50
 */

namespace App\Controller\Blog;

use App;
use App\Controller\AppController;
use Core\HTML\Env\Get;
use Core\Render\Render;


class ArticleController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Categorie");
        $this->loadModel("Blog\Article");
    }

    public function index(){

        $this->posts = $this->Article->all();
        $this->categories = $this->Categorie->all();

        Render::getInstance()->setView("Blog/Index");

    }

    public function categorie(){

        $_id = Get::getInstance()->val('id');

        $this->categorie =  $this->Categorie->find($_id);
        if(!$this->categorie) $this->notFound();
        $this->categories = $this->Categorie->all();
        $this->posts = $this->Article->getListByCategorie($_id);

        Render::getInstance()->setView("Blog/Index");
        App::getInstance()->setTitle($this->categorie->nom);
    }

    public function show(){

        $this->post = $this->Article->find(Get::getInstance()->val('id'));
        if(!$this->post) $this->notFound();
        $this->categorie = $this->Categorie->find($this->post->categorie_id);
        $this->categories = $this->Categorie->all();

        Render::getInstance()->setView("Blog/Show");
        App::getInstance()->setTitle($this->post->titre);
    }
}