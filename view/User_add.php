<?php
class User_add extends TwigView {
    
    public function show($rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("user_add.html.twig");

        $template->display(array('rol' => $rol, 'token' => $token));
    }
    
}
?>