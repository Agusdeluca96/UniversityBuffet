<?php
class Expense_modify extends TwigView {
    
    public function show($compra, $productosCompra, $productosTotal, $rol, $token) {

        $twig = $this->returnTwig();
        $template = $twig->loadTemplate("expense_modify.html.twig");

        $template->display(array('productos' => $productosTotal, 'productos_compra' => $productosCompra, 'compra' => $compra[0], 'rol' => $rol, 'token' => $token));
    }  
}
?>