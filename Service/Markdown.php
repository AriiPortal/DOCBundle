<?php
namespace Arii\DocBundle\Service;

class Markdown
{
    private $parser;

    public function __construct()
    {
        require_once '../vendor/parsedown/Parsedown.php';        
        $this->parser = new \Parsedown();
    }

    public function toHtml($text)
    {
        $html = $this->parser->text($text);

        return $html;
    }
}
?>
