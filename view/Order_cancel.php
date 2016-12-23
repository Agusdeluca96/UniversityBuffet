<?php
class Order_cancel extends TwigView {
    
    public function show($pedido, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("order_cancel.html.twig");

        $template->display(array('rol' => $rol, 'pedido' => $pedido, 'token' => $token));
          
    }
        
}
?>