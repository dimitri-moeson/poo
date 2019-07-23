<?php

namespace App\Controller\Admin;

use App;
use Core\HTML\Env\File;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;

class FileController extends AppController
{
    /**
     * CategorieController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("File");

        File::getInstance()->setIndex("src");
    }

    /**
     * @param $post
     * @return Form
     */
    private function form_file($type)
    {
        $form = new Form(array( 'type' => $type ));

        $form->input("type", array('type' => "hidden"))
            ->input("nom", array('label' => "Nom"))
            ->input("src", array('type' => "file"))
            ->submit("Enregistrer");

        return $form ;
    }

    /**
     *
     */
    function picture(){

        File::getInstance()->setUploadDir(ROOT."/App/View/Assets/Pictures");

        if(Post::getInstance()->submited()) {

            if(File::getInstance()->save()) {

                $exists = $this->File->sourced(File::getInstance()->getName());

                if(!$exists) {

                    $rec = array(

                        'nom' => Post::getInstance()->val('nom'),
                        'src' => File::getInstance()->getName(),
                        'type' => 'picture',

                    );

                    if ($this->File->create($rec)) {

                        Redirect::getInstance()->setParams(array("id" =>App::getInstance()->getDb()->lasInsertId() ))
                            ->setDom('admin')->setAct('picture')->setCtl('file')
                            ->send();
                    }
                }
            }

        }

        $this->files = $this->File->allOf("picture");

        $this->form = $this->form_file("picture");

        Render::getInstance()->setView("Admin/File");
    }

    /**
     *
     */
    function style(){

        File::getInstance()->setUploadDir(ROOT."/App/View/Assets/Styles");

        if(Post::getInstance()->submited()) {

            if($this->File->create( Post::getInstance()->content())){

                if(File::getInstance()->save()) {

                    $this->File->update(App::getInstance()->getDb()->lasInsertId(),array(

                        'name' => File::getInstance()->getName()
                    ));

                    Redirect::getInstance()->setParams(array("id" =>App::getInstance()->getDb()->lasInsertId() ))
                        ->setDom('admin')->setAct('style')->setCtl('file')
                        ->send();
                }
            }

        }

        $this->files = $this->File->allOf("style");

        $this->form = $this->form_file("style");

        Render::getInstance()->setView("Admin/File");
    }

    /**
     *
     */
    function script(){

        File::getInstance()->setUploadDir(ROOT."/App/View/Assets/Scripts");

        if(Post::getInstance()->submited()) {

            if($this->File->create( Post::getInstance()->content())){

                if(File::getInstance()->save()) {

                    $this->File->update(App::getInstance()->getDb()->lasInsertId(),array(

                        'name' => File::getInstance()->getName()
                    ));

                    Redirect::getInstance()->setParams(array("id" =>App::getInstance()->getDb()->lasInsertId() ))
                        ->setDom('admin')->setAct('script')->setCtl('file')
                        ->send();
                }
            }

        }

        $this->files = $this->File->allOf("script");

        $this->form = $this->form_file("script");

        Render::getInstance()->setView("Admin/File");
    }
}