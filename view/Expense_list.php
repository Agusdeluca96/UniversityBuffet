<?php
class Expense_list extends TwigView {
    
    public function show($total, $num_pagina, $compras, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("expense_list.html.twig");

        $template->display(array('total_paginas' => $total, 'num_pagina' => $num_pagina, 'compras' => $compras, 'rol' => $rol));
          
    }  
}
?>