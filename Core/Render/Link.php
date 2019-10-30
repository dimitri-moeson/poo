<?php


namespace Core\Render;

class Link
{
    /**
     * Link constructor.
     * @param $url
     * @param $txt
     * @param array $attrib
     */
    public function __construct($url, $txt, $attrib = array()  )
    {
        $this->url = $url ;
        $this->txt = $txt ;
        $this->attrib = $attrib ;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $attribs = "";

        foreach ($this->attrib as $k => $v) {
            $attribs .= " $k='$v' ";
        }

        if($this->url != "error...") {
            return "<a $attribs href='" . $this->url . "'>" . $this->txt . "</a>";
        }
        return "<a $attribs >{".$this->txt."}</a>";
    }
}