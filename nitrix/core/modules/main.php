<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER[DOCUMENT_ROOT]."/nitrix/core/config/config.php";
if(file_exists($_SERVER[DOCUMENT_ROOT]."/nitrix/core/config/init.php")) 
    include $_SERVER[DOCUMENT_ROOT]."/nitrix/core/config/init.php";
if(empty($template)){//default
    $template_name="default";
    if(!file_exists($_SERVER[DOCUMENT_ROOT]."/nitrix/core/templates/{$template_name}/template.php")) $template_name=false; 
}else{
    $template_name=$template;
    if(!file_exists($_SERVER[DOCUMENT_ROOT]."/nitrix/core/templates/{$template_name}/template.php")) 
        if(!file_exists($_SERVER[DOCUMENT_ROOT]."/nitrix/core/templates/default/template.php")) $template_name=false;
        else $template_name="default"; 
}
if(!$template_name) die("Не найден шаблон!");
    $template_folder="/nitrix/core/templates/{$template_name}";
    $template_text=implode("",file($_SERVER[DOCUMENT_ROOT]."/nitrix/core/templates/{$template_name}/template.php"));
    $header_template=current(explode("#WORK_AREA#",$template_text));
    $footer_template=end(explode("#WORK_AREA#",$template_text));
    if(!is_dir($_SERVER[DOCUMENT_ROOT]."/nitrix/core/tmp/")) rmdir($_SERVER[DOCUMENT_ROOT]."/nitrix/core/tmp/");
    $header_tmp_path=$_SERVER[DOCUMENT_ROOT]."/nitrix/core/tmp/{$template_name}_header_".session_id().".php";
    $footer_tmp_path=$_SERVER[DOCUMENT_ROOT]."/nitrix/core/tmp/{$template_name}_footer_".session_id().".php";
    file_put_contents($header_tmp_path,$header_template);
    file_put_contents($footer_tmp_path,$footer_template);
    ob_start();
        include $header_tmp_path;
        $header = ob_get_contents();
    ob_end_clean(); 
    ob_start();
        include $footer_tmp_path;
        $footer = ob_get_contents();
    ob_end_clean();
    unlink($header_tmp_path);
    unlink($footer_tmp_path);

/*---------Functions ---------*/
//функция нормального читабельного вида
function pr($a){
    print "<pre>"; print_r($a); print "</pre>";
}
//функция дампа в файл(в корне сайта создается папка dump)
function dump($filename,$a){
    if(!is_dir($_SERVER[DOCUMENT_ROOT]."/dump")) mkdir($_SERVER[DOCUMENT_ROOT]."/dump");
    file_put_contents($_SERVER[DOCUMENT_ROOT]."/dump/{$filename}.txt",var_export($a,true));
}
//отложенные функции
function delay_function(&$text_page,$template_params){
    if(!empty($template_params)){    
        $keys=array_keys($template_params);
        foreach($keys as &$v)
            $v="#{$v}#";
        $text_page=str_replace($keys,$template_params,$text_page);
    }    
}
?>