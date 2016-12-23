<?php
class SummaryColumnsChart extends TwigView {
    
    public function show($text, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("columns_chart.html.twig");
        $template->display(array('info' => $text, 'rol' => $rol));
    }  
}
?>