<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 22/05/2019
 * Time: 22:42
 */

namespace Core\Controller;


class Picture
{
    /**
     * readfile
     */
    public function __construct($path){

        //$page = explode('.', $path );


        $this->path = '/App/View/Assets/Pictures/'.$path ; // implode('/', array_map("ucfirst", $page));
        //$this->ext = $ext ;
    }

    /**
     *
     */
    public function read()
    {
        header('Content-Type: image/gif');

        if(file_exists(ROOT.$this->path))
        {
            $mime = mime_content_type(ROOT.$this->path);

            if($mime == "image/gif"){
                $im = @imagecreatefromgif(ROOT.$this->path);
            }

            if($mime == "image/jpeg"){
                $im = @imagecreatefromjpeg(ROOT.$this->path);
            }

            if($mime == "image/png"){
                $im = @imagecreatefrompng(ROOT.$this->path);
            }
        }

        /* Traitement si l'ouverture a échoué */
        if(!$im)
        {
            /* Création d'une image vide */
            $im = imagecreatetruecolor (150, 30);
            $bgc = imagecolorallocate ($im, 255, 255, 255);
            $tc = imagecolorallocate ($im, 0, 0, 0);

            imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);

            /* Affiche un message d'erreur dans l'image */
            imagestring ($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
        }

        imagegif($im);
        imagedestroy($im);

    }
}