<?php

namespace AppBundle\Utils;

use Parsedown;

class Markdown
{
    /**
     * @var Parsedown
     */
    private $parser;
    
    /**
     * Markdown constructor.
     */
    public function __construct()
    {
        $this->parser = new Parsedown();
    }
    
    /**
     * @param string $text
     *
     * @return string
     */
    public function toHtml($text)
    {
        return $this->parser->text($text);
    }
}
