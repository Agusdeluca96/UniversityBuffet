<?php

class Home extends TwigView {
    
    public function show($configuracion, $menuDelDia, $rol) {

        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("home.html.twig");
    	
        $template->display(array('rol' => $rol, 'configuracion' => $configuracion, 'menu' => $menuDelDia));

    }
}
?>