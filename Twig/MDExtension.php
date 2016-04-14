<?php
namespace Arii\DocBundle\Twig;

use Arii\DocBundle\Service\Markdown;

class MDExtension extends \Twig_Extension
{
    private $parser;

    public function __construct(Markdown $parser)
    {
        $this->parser = $parser;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'md2html',
                array($this, 'markdownToHtml'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFilter(
                'text2html',
                array($this, 'textToHtml'),
                array('is_safe' => array('html'))
            ),            
        );
    }

    public function textToHtml($content) {
        $content = str_replace("\n","<br/>",$content);
        $content = str_replace(" ","&nbsp;",ltrim($content));
        $content = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",ltrim($content));
        return $content;
    }
    
    public function markdownToHtml($content)
    {
        return $this->parser->toHtml($content);
    }

    public function getName()
    {
        return 'doc_extension';
    }
}
?>