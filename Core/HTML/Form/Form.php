<?php

namespace Core\HTML\Form;
/**
 * Class Form
 * genere formulaire
 * @package App\Form
 */
class Form
{
    /** @var array données utilisées par le form */
    private $data = array();
    private $configs = [];
    private $separations = [];

    /**
     * @param $form
     *
     * @return $this
     */
    private function token(string $form):?self
    {
        $token = Session::getInstance()->generateFormToken($form);
        $this->input("token");
        $this->inputs["token"]->setType("hidden")->setValue($token);
        return $this;
    }

    /**
     * Form constructor.
     *
     * @param array $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $method
     * @param array $options
     *
     * @return string
     */
    public function init(string $method = "POST", array $options = []): ?self
    {
        $action = $options["action"] ?? "";
        $name = $options["name"] ?? "form";
        //$this->surround = $options["surround"] ?? $this->surround;
        $attr = isset($options["class"]) ? " class='" . $options["class"] . "' " : "";
        $this->token($name);
        $this->configs = [
            "method" => $method,
            "action" => $action,
            "attr" => $attr,
        ];
        return $this;
    }

    /**
     * @var array
     */
    private $inputs = [];
    /**
     * @param $name
     *
     * @return \Core\HTML\Input|null
     */
    public function getInput(string $name): ?Input
    {
        return $this->inputs[$name] ?? null;
    }


    public function addInput($name,$input){

        $this->inputs[$name] = $input ;

        return $this ;
    }
    /**
     * @param $index
     *
     * @return mixed|null
     */
    private function getValue(string $index)
    {
        if (is_object($this->data)) {
            if(isset($this->data->$index))
                return $this->data->$index;

            return null ;
        }
        else
            return $this->data[$index] ?? null;
    }
    /**
     * @param       $name
     * @param array $options
     *
     * @return string
     */
    public function input(string $name, array $options = []):?self
    {
        $value    = $options['value'] ?? $this->getValue($name);
        //$surround = $options['surround'] ?? 'p';

        $options['type'] = $options['type'] ?? "text";
        $this->inputs[$name] = new Input($name, $options);
        if( $this->inputs[$name] instanceof Input)
        {
            $this->inputs[$name]->setValue($value);//->surround($surround);
        }
        return $this;
    }

    public function pswd(string $name, array $options = []):?self
    {
        $value    = $options['value'] ?? $this->getValue($name);
        //$surround = $options['surround'] ?? 'p';

        $options['type'] = $options['type'] ?? "password";

        $this->inputs[$name] = new Input($name, $options);
        if( $this->inputs[$name] instanceof Input)
        {
            $this->inputs[$name]->setValue($value);//->surround($surround);
        }

        if($options['conf'])
        {
            $options['label'] = "Confirmer ".strtolower($options['label']);

            $this->inputs[$name."_conf"] = new Input($name."_conf", $options);
            if( $this->inputs[$name."_conf"] instanceof Input)
            {
                $this->inputs[$name."_conf"]->setValue($value);//->surround($surround);
            }
            $options['conf'] = false ;
        }

        return $this;
    }

    /**
     * @param       $name
     * @param array $options
     *
     * @return string
     */
    public function textarea(string $name, array $options = []):?self
    {
        $this->input($name, $options);
        $this->inputs[$name]->setType("textarea");
        return $this;
    }
    /**
     * @param       $name
     * @param array $options
     * @param array $choices
     *
     * @return $this
     */
    public function choice(string $name, array $options = [], array $choices = []):?self
    {
        $type = $options['type'] ?? "radio";
        $this->input($name, $options);

        if( $this->inputs[$name] instanceof Input)
        {
            $this->inputs[$name]->setChoices($choices)->setType($type);
        }

        return $this;
    }
    /**
     * @param       $name
     * @param array $options
     * @param array $choices
     *
     * @return $this
     */
    public function select(string $name, array $options = [], array $choices = []):?self
    {
        $this->input($name, $options);
        $this->inputs[$name]->setChoices($choices)->setType("select");
        return $this;
    }
    /**
     * @param $send
     *
     * @return string
     */
    public function submit(string $send):?self
    {
        $this->input("submit");
        $this->inputs["submit"]->setType("submit")->setValue($send);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = "<form enctype='multipart/form-data' method='" . ( $this->configs["method"] ?? "post" ) . "' " . ($this->configs["attr"] ?? '') . " action='" . ($this->configs["action"] ?? '') . "'>";
        foreach ($this->inputs as $name => $html_input)
        {
            if( ($this->inputs[$name] instanceof Input) && $this->inputs[$name]->getType() ==="hidden")
            {
                $string .= $html_input ;
            }
            else
            {
                $string .= "<p>".$html_input."</p><br/>";
            }
        }
        $string .= "</form>";
        return $string;
    }

}