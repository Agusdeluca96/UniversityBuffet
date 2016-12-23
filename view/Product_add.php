<?php
class Product_add extends TwigView {
    
    public function show($categoriesArray, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("product_add.html.twig");

        $template->display(array('categorias' => $categoriesArray, 'rol' => $rol, 'token' => $token));
          
    }
    
}
?>