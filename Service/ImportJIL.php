<?php
namespace Arii\DocBundle\Service;

class ImportJIL {
    
    
    public function __construct() {
    }

    public function Import($content, $File) {                 
        $nsub = 1;
        $Parts = array();  
        $Bloc = $Comment = array();
        foreach (explode("\n",$content) as $line) {
            // on retrouve les labels        
            if (preg_match('/insert_job: ([\w\-\_\.]*)/',$line,$Match)) {
                // ancien bloc
                if (isset($Job['name'])) {
                    $Parts[$nsub] = $this->Job($Job,implode("\n",$Bloc));
                    $Exploit =  $this->Exploit($Job);
                    if (!empty($Exploit))
                        $Parts[$nsub]['exploit'] = $Exploit;
                    $nsub++;
                }
                $Job = array( 
                    'name' => $this->Clean($Match[1]),
                    'comment' => implode("\n",$Comment) );
                $Comment = array();
                if (trim($line)!='')
                    $Bloc = array(trim($line));
            }
            elseif (preg_match('/^\s*(\w*?):\s*(.*)/',$line,$Match)) {
                $name = $Match[1];
                $Job[$name] = $this->Clean($Match[2]);
                if (trim($line)!='')
                    array_push($Bloc, trim($line));
            }
            elseif (preg_match('/\/\*/',$line,$Match)) {
                array_push($Comment, trim($line));
            }
            else {
                if (trim($line)!='')
                    array_push($Bloc, trim($line));
            }
        }
        if (isset($Job['name'])) {
            $Parts[$nsub] = $this->Job($Job,implode("\n",$Bloc));
            $Exploit =  $this->Exploit($Job);
            if (!empty($Exploit))
                $Parts[$nsub]['exploit'] = $Exploit;
        }
        $File['parts'] = $Parts;
        $File['count'] = $nsub++;

        return $File;
    }

    protected function Clean($text) {
        $text = trim($text);
        $text = preg_replace('/^\"/','',$text);
        $text = preg_replace('/\"$/','',$text);
        return $text;
    }
    
    protected function Job($Job,$bloc) {
        $New = array(
            'code' => $bloc );
        $Trans = array( 
            'name' =>        'name',
            'description' => 'description',
            'comment' => 'comment',
            'condition' => 'constraints',
            'days_of_week' => 'triggers',
            'start_times' => 'triggers'
            );
        foreach ($Trans as $jil=>$doc) {
            if (isset($Job[$jil]))
                $val = $Job[$jil];
            else 
                $val = '';
            if (isset($New[$doc]))
                $New[$doc] .= ' '.$val;
            else
                $New[$doc] = $val;
        }
        return $New;
    }
    
    protected function Exploit($Job) {
        $Exploit = array();
        
        $Exit = array( '%' ); // au moins une consigne par défaut
/*        
        if (isset($Job['max_exit_success'])) {
            array_push($Exit, $Job['max_exit_success']+1);
        }
        if (isset($Job['failure_codes'])) {
            foreach (explode(",",$Job['failure_codes']) as $e) {
                array_push($Exit, $e);
            }
        }
*/        
        // decoupage script et parametres
        if (isset($Job['command'])) {
            $command = $Job['command'];
            if (preg_match('/(.*?\.)(pl|cmd|sh) (.*)/',$command,$Match)) {
                $call = $Match[1].$Match[2];  
                $parms = trim($Match[3]);
            }
            else {
                $Command = explode(" ",$command);
                $call = array_shift($Command);
                $parms = implode(" ",$Command);
            }
        }
        elseif (isset($Job['watch_file'])) {
            $call = 'file_watcher';  
            $parms = $Job['watch_file'];
        }
        else {
            $call = '';  
            $parms = '';            
        }
        
        $message = 'JOBFAILURE '.$Job['name'];
        $n=0;
        foreach ($Exit as $e) {
            $Exploit[$n]['message'] = $message;
            $Exploit[$n]['exit'] = trim($e); // n'importe quel code                
            $Exploit[$n]['call'] = $call;
            $Exploit[$n]['parms'] = $parms;
            $n++;
        }
        
        // est ce qu'on a u TERM_RUN_TIME ?
        if (isset($Job['term_run_time'])) {
            $messsage = 'KILLJOB '.$Job['name'];
            $Exploit[$n]['message'] = $message;
            $Exploit[$n]['exit'] = trim($e); // n'importe quel code                
            $Exploit[$n]['call'] = $call;
            $Exploit[$n]['parms'] = $parms;
        }
        return $Exploit;
    }
    
}