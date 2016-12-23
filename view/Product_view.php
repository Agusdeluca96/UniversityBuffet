<?php
class Product_view extends TwigView {
    
    public function show($categ, $product, $rol) {

        $twig = $this->returnTwig();
        $template = $twig->loadTemplate("product_view.html.twig");
        
        $template->display(array('categorias' => $categ, 'producto' => $product[0], 'rol' => $rol));
    }  
}
?>