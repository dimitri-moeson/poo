<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 30/04/2019
 * Time: 23:36
 */

namespace Core\HTML\Form;


use DateTime;

class Input
{
    private $type;
    private $label;
    private $value;
    private $name;
    private $class;
    private $i_name;
    /**
     * @var Surround $surround
     */
    private $surround;
    /**
     * @var array
     */
    private $options;

    /**
     * Input constructor.
     *
     * @param       $name
     * @param array $options
     */
    public function __construct($name, $options = [])
    {
        $this->name = $name;
        $this->i_name = $options['name'] ?? $name;
        $this->type = $options['type'] ?? "text";
        $this->label = $options['label'] ?? $name;
        $this->value = $options["value"] ?? null;
        $this->class = $options["class"] ?? null;
        $this->options = $options ?? array() ;
        $this->surround = new Surround("surround_".$name);
    }

    /**
     * @param mixed|null $value
     *
     * @return Input|null
     */
    public function setValue($value): ?Input
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param mixed|string $type
     *
     * @return \Core\HTML\Input|null
     */
    public function setType($type): ?Input
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param array $choises
     *
     * @return \Core\HTML\Input|null
     */
    public function setChoices(array $choises = []): ?Input
    {
        $this->choices = $choises;
        return $this;
    }

    /**
     * @return mixed
     */
    private function getLabel(): ?string
    {
        //return $this->label;
        $class_label = "";
        if ($this->type != "hidden" && $this->type != "submit") {
            $html_label = "<label " . $class_label . " for='" . $this->i_name . "'>" . $this->label . "</label><br/>";
        } else {
            $html_label = "";
        }
        return $html_label;
    }

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    private function string_options(){

        $r = "" ;
        $v_options = array("disabled","readonly");
        if(isset($this->options) &&!empty($this->options)) {
            foreach ($this->options as $k => $v) {
                if (is_string($v)) {
                    if(in_array($k,$v_options))
                        $r .= "$k='$v' ";
                }
            }
        }
        return $r ;

    }

    /**
     * @return string
     */
    public function __toString()
    {
        $html_label = $this->getLabel();
        $html_option = $this->string_options();
        switch ($this->type) {
            case 'file':
                $html_input = "<input class='form-control " . $this->class . "' ".$html_option." placeholder='" . $this->label . "' type='file' id='" . $this->type . "-" . $this->name . "' name='" . $this->i_name . "' value='" . $this->value . "' />";

                break;
            case 'submit':
                $html_input = "<button value='" . $this->value . "' class='btn btn-info' ".$html_option." name='" . $this->i_name . "' id='button-" . $this->name . "' type='" . $this->type . "'>" .
                    ( $this->label ?? $this->value ) . "</button>";
                break;
            case 'select':
                $html_input = "<select class='form-control " . $this->class . "' ".$html_option."  id='select-" . $this->name . "' name='" . $this->i_name . "'><option disabled selected value>--Select--</option>" . "\n\r";
                $html_input .= $this->recurcive_option($this->choices,$this->value);
                $html_input .= "</select>" ;
                break;
            case 'radio':
            case 'checkbox':
                $html_input = "";
                foreach ($this->choices as $k => $v) {
                    $attr = ($k == $this->value ? "checked" : "");
                    $html_input .= "<input class='form-check-input " . $this->class . "' ".$html_option." type='" . $this->type . "' " . $attr . " name='" . $this->i_name . "' value='" . $k . "' />&nbsp;" . $v."&nbsp;";
                }
                break;
            case 'textarea':
                $html_input = "<textarea class='form-control " . $this->class . "' ".$html_option." placeholder='" . $this->label . "' id='text-" . $this->name . "' name='" . $this->i_name . "'>" . addslashes($this->value) . "</textarea>";
                break;
            case 'date':
            case 'datetime':

                if(is_string($this->value))
                    $date = DateTime::createFromFormat("Y-m-d",($this->value ?? date("Y-m-d")));
                elseif($this->value instanceof DateTime)
                    $date = $this->value;

                $html_input = "<input type='text' class='form-control " . $this->class . " datepicker' data-date-format='dd/mm/yyyy'  ".$html_option." placeholder='" . $this->label . "' id='date-" . $this->name . "' name='" . $this->i_name . "' value='".$date->format("d/m/Y")."' />";
                break;
            default:
                $html_input = "<input class='form-control " . $this->class . "' ".$html_option." placeholder='" . $this->label . "' type='" . $this->type . "' id='" . $this->type . "-" . $this->name . "' name='" . $this->i_name . "' ".'value="' . $this->value . '"'." />";
                break;
        }

        if( $this->type ==="hidden")
        {
            return $html_input ;
        }
        else
        {
            $s = $this->surround ;
            return ("<$s>".$html_label . "\n\r" . $html_input."</$s>");
        }

    }

    private function recurcive_option(Array $choices = array(), $value = null ){

        $content = "";
        foreach ($choices as $k => $v) {

            if(is_array($v)){

                $content .= "<optgroup label='$k'>";
                $content .= $this->recurcive_option($v,$value);
                $content .= "</optgroup>";

            }else {

                $attr = ($k == $value ? "selected" : "");
                $content .= "<option $attr value='" . $k . "'>". $v . "</option>" . "\n\r";
            }

        }

        return $content ;

    }

    /**
     * @return Surround
     */
    public function getSurround(): Surround
    {
        return $this->surround;
    }

    /**
     * @param Surround $surround
     */
    public function setSurround(Surround $surround): Input
    {
        $this->surround = $surround;

        return $this ;
    }

}