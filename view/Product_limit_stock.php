<?php
class Product_limit_stock extends TwigView {
    
    public function show($total, $numero, $productos, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("product_limit_stock.html.twig");

        $template->display(array('total_paginas' => $total, 'num_pagina' => $numero, 'productos' => $productos, 'rol' => $rol));
    }    
}
?>