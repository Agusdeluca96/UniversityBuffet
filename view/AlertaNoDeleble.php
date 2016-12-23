<?php

class AlertaNoDeleble extends TwigView {
    
    public function show() {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("alertaNoDeleble.html.twig");
    	
        $template->display(array());

    }
}
?>