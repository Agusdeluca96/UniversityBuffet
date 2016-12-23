<?php
class SummaryProducts_index extends TwigView {
    
    public function show($rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("summaryProducts_index.html.twig");

        $template->display(array('rol' => $rol, 'token' => $token));
    }  
}
?>