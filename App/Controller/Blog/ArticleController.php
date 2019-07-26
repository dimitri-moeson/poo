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
use Core\HTML\Header\Header;
use Core\Render\Render;


class ArticleController extends AppController
{
    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Article");
        $this->loadModel("Blog\Keyword");

        $this->categories = $this->Article->allOf("categorie");
        $this->recents = $this->Article->recent("article");
        $this->clouds = $this->Keyword->cloud();
    }

    /**
     *
     */
    public function index(){

        $this->posts = $this->Article->allOf("article");

        Render::getInstance()->setView("Blog/Index");

    }

    /**
     *
     */
    public function categorie(){

        $_id = Get::getInstance()->val('slug');

        $this->categorie =  $this->Article->recup($_id);
        if(!$this->categorie) $this->notFound();

        $this->posts = $this->Article->getListByCategorie($_id);

        $keywords = $this->Keyword->index(Get::getInstance()->val('id'));

        Render::getInstance()->setView("Blog/Index");
        Header::getInstance()->setTitle($this->categorie->titre);
        Header::getInstance()->setKeywords(implode(",",$keywords ));
        Header::getInstance()->setDescription($this->categorie->description);
    }

    /**
     *
     */
    public function show(){

        $_id = Get::getInstance()->val('slug');
        $this->post = $this->Article->recup($_id);
        if(!$this->post) $this->notFound();

        $this->categorie = $this->Article->find($this->post->parent_id);

        $keywords = $this->Keyword->index($_id);

        Render::getInstance()->setView("Blog/Show");
        Header::getInstance()->setTitle($this->post->titre);
        Header::getInstance()->setKeywords(implode(",",$keywords ));
        Header::getInstance()->setDescription($this->post->description);

    }
}