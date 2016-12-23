<?php
class Expense_product_add extends TwigView {
    
    public function show($rol) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("expense_product_add.html.twig");
    	
        $template->display(array('rol' => $rol));
          
    }
    
}
?>