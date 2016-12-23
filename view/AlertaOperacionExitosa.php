<?php

class AlertaOperacionExitosa extends TwigView {
    
    public function show() {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("alertaOperacionExitosa.html.twig");
    	
        $template->display(array());

    }
}
?>