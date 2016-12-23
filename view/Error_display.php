<?php
class Error_display extends TwigView {
    
    public function show($error) {
        
	    $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("error_display.html.twig");

    	$template->display(array('rol' => $_SESSION['rol'], 'error' => $error));               
    }   
}
?>