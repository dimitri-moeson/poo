<?php

namespace Core\HTML\Form;
use Core\HTML\Env\GlobalRequest;

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
    private $surround;

    /**
     *
     */
    public function submition()
    {
        if(GlobalRequest::getInstance()->submit())
        {
            $submit = array();
            foreach ($this->inputs as $name => $input)
            {
                if(GlobalRequest::getInstance()->has($name))
                {
                    if ($name !== "token" && $name !== "submit")
                    {
                        $value = GlobalRequest::getInstance()->val($name);

                        $submit[$name] = $value;
                    }
                }
            }

            return $submit ;
        }

        return false ;
    }

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

        $this->surround = new Surround("default");
        $this->surround->setType("div");
        $this->surround->setClass("row col-sm-12");
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

    /**
     * @param $name
     * @param $input
     * @return $this
     */
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

        $options['name'] = $options['name'] ?? $name;
        $options['type'] = $options['type'] ?? "text";
        $surround = $options['surround']['type'] ?? $this->surround->getType() ?? 'div';
        $s_cls = $options['surround']['class'] ?? $this->surround->getClass() ?? 'row col-sm-12';

        $this->inputs[$name] = new Input($name, $options);

        if( $this->inputs[$name] instanceof Input)
        {
            $this->inputs[$name]->setValue($value);//->surround($surround);
            $this->inputs[$name]->getSurround()->setType($surround);
            $this->inputs[$name]->getSurround()->setClass($s_cls);
        }

        $options['surround']['type'] = $surround ;
        $options['surround']['class'] = $s_cls ;

        if(isset($options['conf'])) $this->confirm_input($name,$options);

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return Form|null
     */
    public function pswd(string $name, array $options = []):?self
    {
        $this->input($name, $options);
        $this->inputs[$name]->setType("password");

        return $this;
    }

    public function number(string $name, array $options = []):?self
    {
        $this->input($name, $options);
        $this->inputs[$name]->setType("number");

        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function confirm_input(string $name, array $options = []){

        if($options['conf'])
        {
            $value = $options['value'] ?? $this->getValue($name);

            $options['label'] = "Confirmer ".strtolower($options['label']);
            $options['name'] = $options['name']."_conf" ?? $name."_conf";

            $this->inputs[$name."_conf"] = new Input($name."_conf", $options);
            if( $this->inputs[$name."_conf"] instanceof Input)
            {
                $this->inputs[$name."_conf"]->setValue($value);
            }
            $options['conf'] = false ;
        }
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
    public function submit(string $send, array $options = []):?self
    {
        $this->input("submit",$options);
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
            $string .= $html_input ;
        }
        $string .= "</form>";
        return $string;
    }

    /**
     * @param mixed $surround
     * @return Form
     */
    public function setSurround($surround)
    {
        $this->surround = $surround;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSurround()
    {
        return $this->surround;
    }
}