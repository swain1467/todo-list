<?php
function arrayToStr($arr){
    return json_encode($arr);
}

class ErrorLog{
    public static function log($content){
        if(is_array($content))
        {
            $content = arrayToStr($content);
        }
        $user_name = $_SESSION['user_name'];
        
        $date = date('d-m-y');
        $time = date('d-m-y h:i:s');
        
        if(file_exists(ROOT_DIR."log/".$date.".txt")){
            $file = fopen(ROOT_DIR."log/".$date.".txt","a");
            fwrite($file,$time."::".$user_name."\n");
            fwrite($file,$content."\n\n");
            fclose($file);
        } else{
            $file = fopen(ROOT_DIR."log/".$date.".txt","w");
            fwrite($file,$time."::".$user_name."\n");
            fwrite($file,$content."\n\n");
            fclose($file);
        }
    }
}

class DefaultErrorLog{
    public static function defaultLog($content){

        if(is_array($content))
        {
            $content = print_r($content, true);
        }
        error_log($content."\n",0);
    }
}
?>