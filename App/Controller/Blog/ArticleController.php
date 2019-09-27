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
use App\Model\Service\ArticleService;
use App\Model\Table\Blog\ArticleTable;
use Core\Auth\DatabaseAuth;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

/**
 * Class ArticleController
 * @package App\Controller\Blog
 */
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

        $this->loadService("Article");

        $this->categories = $this->Article->allOf("categorie");
        $this->recents = $this->Article->recent("article");
        $this->clouds = $this->Keyword->cloud("article");

        $this->auth = new DatabaseAuth(App::getInstance()->getDb());
    }

    /**
     *
     */
    public function index(){

        $this->posts = $this->Article->allOf("article");

        Render::getInstance()->setView("Blog/Index");
    }

    /**
     * @param $_id
     */
    public function categorie($_id){

        //$_id = Get::getInstance()->val('slug');

        $this->categorie =  $this->Article->recup($_id);
        if(!$this->categorie) $this->notFound("categorie");

        $this->posts = $this->Article->getListByCategorie($_id);

        $keywords = $this->Keyword->index(Get::getInstance()->val('id'));

        Render::getInstance()->setView("Blog/Index");
        Header::getInstance()->setTitle($this->categorie->titre);
        Header::getInstance()->setKeywords(implode(",",$keywords ));
        Header::getInstance()->setDescription($this->categorie->description);
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_comment($parent_id)
    {
        if($this->auth->logged()){
            $form = new Form();

            $form
                ->input("contenu", array('type' => 'textarea', 'label' => "Commentaire", "class" => "editor"))
        /**
                //->input("titre", array("type" => "hidden", "value" => "commentaire-$parent_id"))
                //->input("type", array("type" => "hidden", "value" => "commentaire"))
                //->input("parent_id", array("type" => "hidden", "value" => $parent_id))
                //->input("author_id", array("type" => "hidden", "value" => $this->auth->getUser('id')))
                //->input("date", array("type" => "hidden", "value" => date("Y-m-d H:i:s")))
        **/
                ->submit("Enregistrer");

            return $form;
        }
    }

    /**
     * @param $_id
     */
    public function show($_id)
    {
        if($this->Article instanceof ArticleTable)
        {
            $this->post = $this->Article->recup($_id);
            if (!$this->post) $this->notFound("Article not found");

            if (Post::getInstance()->submit())
            {
                Post::getInstance()->val("titre", "commentaire-" . $this->post->id);
                Post::getInstance()->val("type", "commentaire");
                Post::getInstance()->val("parent_id", $this->post->id);
                Post::getInstance()->val("author_id", $this->auth->getUser('id'));
                Post::getInstance()->val("date", date("Y-m-d H:i:s"));

                if($this->ArticleService  instanceof ArticleService)
                {
                    if ($this->ArticleService->record())
                    {
                        FlashBuilder::create("commentaire ajouté", "success");
                    }
                }
                Redirect::getInstance()->setSlg($this->post->slug)
                    ->setAct("show")->setCtl("article")->setDom("blog")
                    ->send();
            }

            $this->categorie = $this->Article->find($this->post->parent_id);

            $keywords = $this->Keyword->index($_id);

            Render::getInstance()->setView("Blog/Show");
            Header::getInstance()->setTitle($this->post->titre);
            Header::getInstance()->setKeywords(implode(",", $keywords));
            Header::getInstance()->setDescription($this->post->description);

            $this->form = $this->auth->logged() ? $this->form_comment($this->post->id) : null;

            $this->comments = $this->Article->allOf("commentaire", $this->post->id);
        }
    }

    public function keywords($word){

        if($this->Article instanceof ArticleTable) {
            $this->posts = $this->Article->getListByKey($word);
        }
        Render::getInstance()->setView("Blog/Index");
    }
}