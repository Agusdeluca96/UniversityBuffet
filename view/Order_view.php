<?php
class Order_view extends TwigView {
    
    public function show($productsOrder, $order, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("order_view.html.twig");

        $template->display(array('productos' => $productsOrder, 'pedido' => $order, 'rol' => $rol));
          
    }
    
}
?>