<?php
class ErrorLog{
    public static function log($content){
        if(is_array($content))
        {
            $content = self::arrayToStr($content);
        }
        $user_name = $_SESSION['user_name'];
        
        $date = date('d-m-y');
        $time = date('d-m-y h:i:s');
        
        if(file_exists("../log/".$date.".txt")){
            $file = fopen("../log/".$date.".txt","a");
            fwrite($file,$time."::".$user_name."\n");
            fwrite($file,$content."\n\n");
            fclose($file);
        } else{
            $file = fopen("../log/".$date.".txt","w");
            fwrite($file,$time."::".$user_name."\n");
            fwrite($file,$content."\n\n");
            fclose($file);
        }
    }

    private static function arrayToStr($arr){
        return json_encode($arr);
    }
}
?>