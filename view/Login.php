<?php
class Login extends TwigView {
    
    public function show($rol, $token) {
        
	    $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("login.html.twig");

    	$template->display(array('rol' => $rol, 'token' => $token));               
    }   
}
?>