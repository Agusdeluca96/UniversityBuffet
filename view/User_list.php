<?php
class User_list extends TwigView {
    
    public function show($rol, $total, $numero_pagina, $result) {

        $twig = $this->returnTwig();
        $template = $twig->loadTemplate("user_list.html.twig");
        
        $template->display(array('total_paginas' => $total, 'num_pagina' => $numero_pagina, 'usuarios' => $result, 'rol' => $rol));
    }  
}
?>