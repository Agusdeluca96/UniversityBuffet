<?php
class Expense_product_list extends TwigView {
    
    public function show($expensesProductArray) {
        
        $twig = $this->returnTwig();
    	$template = $twig->loadTemplate("expense_product_list.html.twig");

        $template->display(array('compra_producto' => $expensesProductArray, 'rol' => $_SESSION['rol']));
          
    }
    
}
?>