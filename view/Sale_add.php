<?php
class Sale_add extends TwigView {
    
    public function show($products, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("sale_add.html.twig");
    	
        $template->display(array('productos' => $products, 'rol' => $rol, 'token' => $token));
          
    }
    
}
?>