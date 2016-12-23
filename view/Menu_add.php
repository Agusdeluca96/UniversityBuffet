<?php
class Menu_add extends TwigView {
    
    public function show($productsArray, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("menu_add.html.twig");

        $template->display(array('productos' => $productsArray, 'rol' => $rol, 'token' => $token));
        
    }
    
}
?>