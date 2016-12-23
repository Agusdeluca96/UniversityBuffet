<?php

class AlertaOrdenNoPendiente extends TwigView {
    
    public function show() {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("alertaOrdenNoPendiente.html.twig");
    	
        $template->display(array());

    }
}
?>