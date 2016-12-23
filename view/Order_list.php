<?php
class Order_list extends TwigView {
    
    public function show($total, $numero_pagina, $result, $desde, $hasta, $users, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("order_list.html.twig");

        $template->display(array('total_paginas' => $total, 'num_pagina' => $numero_pagina, 'pedidos' => $result, 'rol' => $rol, 'desde' => $desde, 'hasta' => $hasta, 'users' => $users));
          
    }

}
?>