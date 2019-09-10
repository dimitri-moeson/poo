<?php


namespace App\Controller;


use Core\Config;
use Core\HTML\Env\Get;

class ImgController extends AppController
{
    /**
     * TestController constructor. appel des service/table
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel("File");
    }

    /**
     *
     */
    function index()
    {
        $name = Get::getInstance()->val("src");

        $file = $this->File->named($name);

        $this->read($file->src);
    }

    /**
     * @param $path
     */
    private function read($path)
    {
        header('Content-Type: image/gif');

        $imgName = Config::VIEW_DIR."/Assets/Pictures/".$path ;

        if(file_exists($imgName))
        {
            $mime = mime_content_type($imgName);

            if($mime == "image/gif"){
                $im = @imagecreatefromgif($imgName);
            }

            if($mime == "image/jpeg"){
                $im = @imagecreatefromjpeg($imgName);
            }

            if($mime == "image/png"){
                $im = @imagecreatefrompng($imgName);
            }
        }

        /** Traitement si l'ouverture a échoué */
        if(!$im)
        {
            /* Création d'une image vide */
            $im = imagecreatetruecolor (150, 30);
            $bgc = imagecolorallocate ($im, 255, 255, 255);
            $tc = imagecolorallocate ($im, 0, 0, 0);

            imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);

            /* Affiche un message d'erreur dans l'image */
            imagestring ($im, 1, 5, 5, 'Error loading ' . $imgName, $tc);
        }

        imagegif($im);
        imagedestroy($im);
    }

}