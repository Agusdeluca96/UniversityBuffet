<?php

class AlertaLoginInvalido extends TwigView {
    
    public function show() {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("alertaLoginInvalido.html.twig");
    	
        $template->display(array());

    }
}
?>