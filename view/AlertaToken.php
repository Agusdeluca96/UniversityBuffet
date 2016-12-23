<?php

class AlertaToken extends TwigView {
    
    public function show() {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("alertaToken.html.twig");
    	
        $template->display(array());

    }
}
?>