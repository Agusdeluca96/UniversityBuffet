<?php
class Order_add extends TwigView {
    
    public function show($productsArray, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("order_add.html.twig");

        $template->display(array('productos' => $productsArray, 'rol' => $rol, 'token' => $token));
          
    }
    
}
?>