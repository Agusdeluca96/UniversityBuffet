<?php
class Menu_list extends TwigView {
    
    public function show($total_paginas, $numero_pagina, $arrayMenues, $rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("menu_list.html.twig");

        $template->display(array('total_paginas' => $total_paginas, 'rol' => $rol, 'num_pagina' => $numero_pagina, 'menus' => $arrayMenues));
          
    }
    
}
?>