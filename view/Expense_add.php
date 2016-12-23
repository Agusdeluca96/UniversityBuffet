<?php
class Expense_add extends TwigView {
    
    public function show($products, $rol, $token) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("expense_add.html.twig");
    	
        $template->display(array('productos' => $products, 'rol' => $rol, 'token' => $token));
          
    }
    
}
?>