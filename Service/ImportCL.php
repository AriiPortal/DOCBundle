<?php
namespace Arii\DocBundle\Service;

class ImportCL {
    
    
    public function __construct() {
    }

    public function Import($content, $File) {                 
        $nsub = 0;
        $Parts = array();
        $bloc = $comment = $exploit = $call = $parm = '';
        foreach (explode("\n",$content) as $line) {
            // on retrouve les labels        
            if (preg_match('/^(\w*):/',$line,$Match)
                    or preg_match('/^SUBR (\w*)/',$line,$Match)) {
                // ancien bloc
                if (!isset($label)) {
                    $label = 'MAIN';                    
                }
                
                $Parts[$nsub]['name'] = $label;
                $Parts[$nsub]['code'] = $bloc;
                $Parts[$nsub]['comment'] = $comment;
                $Exploit =  $this->Exploit($bloc);
                if (!empty($Exploit))
                    $Parts[$nsub]['exploit'] = $Exploit;
                
                // nouveau bloc
                $label = trim($Match[1]);
                $nsub++;
                $Parts[$nsub]['comment'] = $comment; 
                $comment = '';
                $bloc = $line;
            }            
/*          elseif (preg_match('/ENDSUBR/',$line,$Match)) {
                    $Parts[$nsub]['name'] = $label;
                    $Parts[$nsub]['code'] = $bloc.$line; 
                    $Parts[$nsub]['comment'] = $comment; 
                    unset($label);
            }
*/
            elseif (preg_match('/\s*AUTEUR\s*:\s*([a-zA-Z\. ]*)/i',$line,$Match)) {
                $File['author'] = trim($Match[1]);
            }
            elseif (preg_match('/\/\*(.*?)\*\//',$line,$Match)) {
                $comment .= trim($Match[1])."\n";
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
        $exit = $call = $parm = '';
        $n=0;
        foreach (explode("\n",$code) as $line) {
            // Pilotage
            if (preg_match('/\s*MSGID\s*\((.*?)\)/',$line,$Match)) {
                $exit = trim($Match[1]);
            }
            if (preg_match('/\s*PGM\s*\((.*?)\)/',$line,$Match)) {
                $call = trim($Match[1]);
            }
            if (preg_match('/\s*PARM\s*\((.*?)\)/',$line,$Match)) {
                $parm = trim($Match[1]);
            }
            if (preg_match("/SNDUSRMSG\s*MSG\('(.*?)'\)/",$line,$Match)) {
                $n++;
                $Exploit[$n]['message'] = $Match[1];
                $Exploit[$n]['exit'] = $exit;
                $Exploit[$n]['call'] = $call;
                $Exploit[$n]['parms'] = $parm;
                $exit = $call = $parm = '';
            }
        }
        return $Exploit;
    }
    
}