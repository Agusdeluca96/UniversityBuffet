<?php
class SummaryProductsList extends TwigView {
    
    public function show($desde, $hasta, $total, $num_pagina, $productos, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("summaryProducts_list.html.twig");
        $template->display(array('desde' => $desde, 'hasta' => $hasta,'total_paginas' => $total, 'num_pagina' => $num_pagina, 'productos' => $productos, 'rol' => $rol));
    }  
}
?>