<?php
namespace Arii\DocBundle\Service;

class ImportYADE {
    
    
    public function __construct() {
    }

    public function Import($content, $File) {                 
        $nsub = 0;
        $Parts = array();
        $bloc = $comment = $exploit = $call = $parm = '';
        foreach (explode("\n",$content) as $line) {
            // on retrouve les labels        
            if (preg_match('/\[(.*?)\]/',$line,$Match)) {
                // ancien bloc
                if (isset($label)) {
                    $Parts[$nsub]['name'] = $label;
                    $Parts[$nsub]['code'] = $bloc;
                    $Parts[$nsub]['comment'] = $comment;
                    $Exploit =  $this->Exploit($bloc);
                    if (!empty($Exploit))
                        $Parts[$nsub]['exploit'] = $Exploit;

                    // nouveau bloc
                    $label = trim($Match[1]);
                    $nsub++;
                    $comment = '';
                    $bloc = $line;
                }
                $label = $Match[1];
            }
            elseif (preg_match('/description: "(.*?)"/',$line,$Match)) {
                $comment = trim($Match[1]);
            }
            else {
                $bloc .= "$line\n";
            }
        }
        
        // Ramasse miettes
        if (isset($label)) {
            $Parts[$nsub]['name'] = $label;
            $Parts[$nsub]['code'] = $bloc; 
            $Exploit =  $this->Exploit($bloc);
            if (!empty($Exploit))
                $Parts[$nsub]['exploit'] = $Exploit;
        }
        $File['parts'] = $Parts;
        $File['count'] = $nsub++;
        return $File;
    }

    protected function Exploit($code) {
        $Exploit = array();
        $n=0;
        foreach (explode("\n",$code) as $line) {
        }
        return $Exploit;
    }
    
}