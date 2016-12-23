<?php

class AlertaFechasInvalidas extends TwigView {
    
    public function show() {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("alertaFechasInvalidas.html.twig");
    	
        $template->display(array());

    }
}
?>