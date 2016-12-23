<?php
class SummaryEarnings_index extends TwigView {
    
    public function show($rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("summaryEarnings_index.html.twig");

        $template->display(array('rol' => $rol, 'token' => $token));
    }  
}
?>