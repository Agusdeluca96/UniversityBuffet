<?php
class Product_modify extends TwigView {
    
    public function show($product, $cate, $rol, $token) {

        $twig = $this->returnTwig();
        $template = $twig->loadTemplate("product_modify.html.twig");
        
        $template->display(array('categorias' => $cate, 'producto' => $product[0], 'rol' => $rol, 'token' => $token));
    }  
}
?>