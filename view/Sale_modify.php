<?php
class Sale_modify extends TwigView {
    
    public function show($venta, $productosVenta, $productosTotal, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("sale_modify.html.twig");

        $template->display(array('productos' => $productosTotal, 'productos_venta' => $productosVenta, 'venta' => $venta[0], 'rol' => $rol, 'token' => $token));
          
    }  
}
?>