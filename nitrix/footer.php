<?print $footer;
$text_page = ob_get_contents();
ob_end_clean(); 
delay_function($text_page,$template_params);
print $text_page;
?>