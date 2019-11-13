<?php

namespace App\Controller\Admin;

use App;
use App\View\Form\FileForm;
use Core\Config;
use Core\HTML\Env\File;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

class FileController extends AppController
{
    /**
     * FileController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("File");

        File::getInstance()->setIndex("src");
    }

    /**
     * @param string $type
     */
    public function index($type = "picture"){

        File::getInstance()->setUploadDir(Config::VIEW_DIR."/Assets/".ucfirst($type)."s");

        $this->form = $this->generate(''.$type ); //form_file("picture");

        Render::getInstance()->setView("Admin/File");

    }

    /**
     * @param string $type
     * @return Form
     */
    private function generate($type = 'picture'){

        if(Post::getInstance()->submit()) {

            if(File::getInstance()->save()) {

                $exists = $this->File->sourced(File::getInstance()->getName());

                if(!$exists) {

                    $rec = array(

                        'nom' => Post::getInstance()->val('nom'),
                        'src' => File::getInstance()->getName(),
                        'type' => $type,

                    );

                    if ($this->File->create($rec)) {

                        FlashBuilder::create("fichier ajouté","success");

                        Redirect::getInstance()->setParams(array("id" =>App::getInstance()->getDb()->lasInsertId() ))
                            ->setDom('admin')->setAct(''.$type )->setCtl('file')
                            ->send();
                    }
                }
            }

        }

        $this->files = $this->File->allOf($type);

        return FileForm::form_file("".$type);
    }
}