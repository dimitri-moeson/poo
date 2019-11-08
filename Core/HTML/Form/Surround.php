<?php


namespace Core\HTML\Form;


class Surround
{
    private $id;
    private $type;
    private $name;
    private $class;
    /**
     * @var array
     */
    private $options;


    public function __construct($name, $options = [])
    {
        $this->name = $name;
        $this->type = $options['type'] ?? "div";
        $this->class = $options["class"] ?? "row";
        $this->id = $options["id"] ?? null;

        $this->called = 0;
    }

    public function __toString()
    {
        if($this->called === 0 ){

            $this->called ++ ;

            return $this->type.
                ($this->class ? " class='".$this->class."'":'').
                ($this->name ? " name='".$this->name."'" : '').
                ($this->id ? " id='".$this->id."'":'')
            ;
        }
        elseif($this->called === 1 ){

            $this->called ++ ;
            return $this->type ;
        }
        else {
            return ;
        }
    }

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed|string $type
     */
    public function setType($type): Surround
    {
        $this->type = $type;
        return $this ;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): Surround
    {
        $this->name = $name;
        return $this ;
    }
    /**
     * @return mixed|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param mixed|null $id
     * @return Surround
     */
    public function setId(?string $id = null ): Surround
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param mixed|null $class
     * @return Surround
     */
    public function setClass(?string $class = null): Surround
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return Surround
     */
    public function setOptions(array $options): Surround
    {
        $this->options = $options;
        return $this;
    }

}