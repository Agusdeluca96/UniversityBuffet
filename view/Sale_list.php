<?php
class Sale_list extends TwigView {
    
    public function show($total, $numero_pagina, $result, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("sale_list.html.twig");

        $template->display(array('total_paginas' => $total, 'num_pagina' => $numero_pagina, 'ventas' => $result, 'rol' => $rol));
          
    }  
}
?>