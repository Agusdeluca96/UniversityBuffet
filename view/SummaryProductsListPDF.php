<?php
class SummaryProductsListPDF extends TwigView {
    
    public function show($desde, $hasta, $productos, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("summaryProducts_list_PDF.html.twig");
        $template->display(array('desde' => $desde, 'hasta' => $hasta, 'productos' => $productos, 'rol' => $rol));
    }  
}
?>