<?php
class Config_list extends TwigView {
    
    public function show($config, $rol, $token) {
    
		$twig = $this->returnTwig();
    	$template = $twig->loadTemplate("config_list.html.twig");

        $template->display(array('config' => $config, 'rol' => $rol, 'token' => $token));
          
    }  
}
?>