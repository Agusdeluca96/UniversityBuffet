<?php
class Order_list_dates extends TwigView {
    
    public function show($rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("order_list_dates.html.twig");

        $template->display(array('rol' => $rol, 'token' => $token));
          
    }

}
?>