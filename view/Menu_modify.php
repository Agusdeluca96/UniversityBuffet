<?php
class Menu_modify extends TwigView {
    
    public function show($productos, $productos_menu, $menu, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("menu_modify.html.twig");

        $template->display(array('menu' => $menu, 'rol' => $rol, 'productos' => $productos, 'productos_menu' => $productos_menu, 'token' => $token));
          
    }
    
}
?>