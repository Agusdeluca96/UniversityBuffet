<?php
class SummaryPieChart extends TwigView {
    
    public function show($text, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("pie_chart.html.twig");
        $template->display(array('info' => $text, 'rol' => $rol));
    }  
}
?>