<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 19:11
 */

namespace App\Controller;


use App\Model\Table\Blog\ArticleTable;
use Core\HTML\Header\Header;
use Core\Render\Render;
use Exception;

class DefaultController extends AppController
{
    /**
     * DefaultController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Blog\Article");
        $this->loadModel("Blog\Keyword");
        $this->loadModel("Blog\Indexion");

        $this->loadService("Article");
    }

    /**
     *
     * @param null $slug
     */
    public function index($slug = null )
    {
        if($this->Article instanceof ArticleTable)
        {
            if(!is_null($slug) && $slug != "index")
            {
                $this->page = $this->Article->findOneBy(['slug' => $slug ]);
            }
            else
            {
                $this->page = $this->Article->findOneBy(array("type" => "page", "default"=> 1)); // default();
            }
        }

        if($this->page) {
            $keywords = $this->Keyword->index($this->page->id);

            Header::getInstance()->setTitle($this->page->titre);
            Header::getInstance()->setKeywords(implode(",", $keywords));
            Header::getInstance()->setDescription($this->page->description);

            Render::getInstance()->setView("Default/Index");
        }
    }
}