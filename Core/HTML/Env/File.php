<?php

namespace Core\HTML\Env;


class File extends GlobalRequest
{
    private $index ;
    private $upload_dir;

    /**
     * @return mixed
     */
    public function getSize(){

        return $_FILES[$this->index]["size"];

    }

    /**
     * @return mixed
     */
    public function getError(){

        return $_FILES[$this->index]["error"];

    }

    /**
     * basename() peut empêcher les attaques de système de fichiers;
     * la validation/assainissement supplémentaire du nom de fichier peut être approprié
     * @return mixed
     */
    public function getName(){

        return basename($_FILES[$this->index]["name"]);

    }

    /**
     * @return mixed
     */
    public function getMime(){

        return $_FILES[$this->index]["type"];

    }

    /**
     * @return mixed
     */
    public function getTmp(){

        return $_FILES[$this->index]["tmp_name"];
    }

    /**
     * @return bool
     */
    function save(){

        $tmp_name = $this->getTmp();

        if (is_uploaded_file($tmp_name))
        {
            if ($this->getError() == UPLOAD_ERR_OK)
            {
                $name = $this->getName();
                move_uploaded_file("".$tmp_name, $this->upload_dir . "/" . $name);
                return true ;
            }
        }

        return false ;
    }

    /**
     * @param mixed $index
     * @return File
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $upload_dir
     * @return File
     */
    public function setUploadDir($upload_dir)
    {
        $this->upload_dir = $upload_dir;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUploadDir()
    {
        return $this->upload_dir;
    }
}