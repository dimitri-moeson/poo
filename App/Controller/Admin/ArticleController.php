<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */

namespace App\Controller\Admin;

use App;
use App\Model\Service\ArticleService;
use App\View\Form\ArticleForm;
use Core\HTML\Env\Post;
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

/**
 * Class ArticleController
 * @package App\Controller\Admin
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
        $this->loadModel("Blog\Indexion");

        $this->loadService("Article");

        $this->categories = $this->Article->allOf("categorie");
        $this->posts = $this->Article->allOf("article");
    }

    /**
     *
     */
    public function index()
    {
        Render::getInstance()->setView("Admin/Blog/home"); // , compact('posts','categories'));
    }

    /**
     *
     */
    public function add(){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","article");
            if($this->ArticleService instanceof ArticleService) {
                if ($this->ArticleService->record()) {

                    FlashBuilder::create("article créé", "success");

                    Redirect::getInstance()->setParams(array("id" => App::getInstance()->getDb()->lasInsertId()))
                        ->setAct("edit")->setCtl("article")->setDom("admin")
                        ->send();
                }
            }
        }
        $categories = $this->Article->listing('categorie');

        $this->form = ArticleForm::_article(Post::getInstance()->content('post'),[],$categories);

        Render::getInstance()->setView("Admin/Blog/single"); // , compact('form','categories'));
    }

    /**
     *
     */
    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id'))
            {
                $this->post = $this->Article->find(Post::getInstance()->val('id'));
                if (!$this->post) $this->notFound("delete post");
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Article->archive(Post::getInstance()->val('id')))
                {
                    FlashBuilder::create("article supprimé","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("article")
                        ->send();
                }
            }
        }

        Render::getInstance()->setView("Admin/Blog/delete"); // , compact('posts','categories'));
    }

    /**
     * @param $id
     */
    public function single($id){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","article");

            if($this->ArticleService instanceof ArticleService) {
                if ($this->ArticleService->record($id)) {
                    FlashBuilder::create("article modifié", "success");
                }
                Redirect::getInstance()->setParams(array("id" => $id))
                    ->setAct("edit")->setCtl("article")->setDom("admin")
                    ->send();
            }
        }

        if(!is_null($id)){

            $this->post = $this->Article->find($id);
            if (!$this->post) $this->notFound("single post");

            $keywords = $this->Keyword->index($id);
            $categories = $this->Article->listing('categorie');

            Header::getInstance()->setTitle($this->post->titre);

            $this->form = ArticleForm::_article($this->post,$keywords, $categories);

            /**$cnt = $this->form->submition();

            if($cnt !== false){

                Post::getInstance()->val("type","article");

                if($this->ArticleService instanceof ArticleService) {
                    if ($this->ArticleService->record($id)) {
                        FlashBuilder::create("article modifié", "success");
                    }
                    Redirect::getInstance()->setParams(array("id" => $id))
                        ->setAct("edit")->setCtl("article")->setDom("admin")
                        ->send();
                }

            }**/
        }


        Render::getInstance()->setView("Admin/Blog/single"); // , compact('post','categories','success','form'));
    }

    /**
     * @param $id
     */
    public function content($id){

        if(Post::getInstance()->submit()) {

            Post::getInstance()->val("type","article");

            if($this->ArticleService instanceof ArticleService) {
                if ($this->ArticleService->updateContent($id)) {
                    FlashBuilder::create("article modifié", "success");
                }
                Redirect::getInstance()->setParams(array("id" => $id))
                    ->setAct("edit")->setCtl("article")->setDom("admin")
                    ->send();
            }
        }

        if(!is_null($id)){

            $this->post = $this->Article->find($id);
            if (!$this->post) $this->notFound("single post");

            Header::getInstance()->setTitle($this->post->titre);

            $this->form = ArticleForm::_content($this->post);
        }


        Render::getInstance()->setView("Admin/Blog/content"); // , compact('post','categories','success','form'));
    }
}