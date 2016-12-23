<?php
class Product_list extends TwigView {
    
    public function show($total, $numero, $productos, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("product_list.html.twig");

        $template->display(array('total_paginas' => $total, 'num_pagina' => $numero, 'productos' => $productos, 'rol' => $rol));
    }    
}
?>