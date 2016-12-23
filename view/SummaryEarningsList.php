<?php
class SummaryEarningsList extends TwigView {
    
    public function show($desde, $desdeInicio, $hasta, $hastaFin, $total, $num_pagina, $ganancias, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("summaryEarnings_list.html.twig");
        $template->display(array('desde' => $desde, 'desdeInicio' => $desdeInicio, 'hasta' => $hasta, 'hastaFin' => $hastaFin, 'total_paginas' => $total, 'num_pagina' => $num_pagina, 'ganancias' => $ganancias, 'rol' => $rol));
    }  
}
?>