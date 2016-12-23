<?php

class AlertaStockInsuficiente extends TwigView {
    
    public function show() {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("alertaStockInsuficiente.html.twig");
    	
        $template->display(array());

    }
}
?>