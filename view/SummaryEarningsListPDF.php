<?php
class SummaryEarningsListPDF extends TwigView {
    
    public function show($desde, $desdeInicio, $hasta, $hastaFin, $total, $ganancias, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("summaryEarnings_list_PDF.html.twig");
        $template->display(array('desde' => $desde, 'desdeInicio' => $desdeInicio, 'hasta' => $hasta, 'hastaFin' => $hastaFin, 'total_paginas' => $total, 'ganancias' => $ganancias, 'rol' => $rol));
    }  
}
?>