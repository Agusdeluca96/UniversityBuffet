<?php

session_start();
if(!isset($_SESSION['rol'])){
	$_SESSION['rol']=null;
}

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once('controller/ResourceController.php');
require_once('controller/permission.php');
require_once 'vendor/autoload.php';
require_once('model/PDORepository.php');

require_once('model/ProductRepository.php');
require_once('model/Product.php');
require_once('model/UserRepository.php');
require_once('model/Config.php');
require_once('model/ConfigRepository.php');
require_once('model/User.php');
require_once('model/CategoryRepository.php');
require_once('model/Category.php');
require_once('model/Expense.php');
require_once('model/ExpenseRepository.php');
require_once('model/ExpenseProduct.php');
require_once('model/ExpenseProductRepository.php');
require_once('model/Sale.php');
require_once('model/SaleRepository.php');
require_once('model/SaleProduct.php');
require_once('model/SaleProductRepository.php');
require_once('model/Menu.php');
require_once('model/MenuRepository.php');
require_once('model/Order.php');
require_once('model/OrderProduct.php');
require_once('model/OrderRepository.php');
require_once('model/TelegramBotRepository.php');
require_once('model/Earning.php');
require_once('model/SummaryProducts.php');
require_once('model/EarningRepository.php');


require_once('view/TwigView.php');
require_once('view/Home.php');
require_once('view/Login.php');

require_once('view/User_add.php');
require_once('view/User_list.php');
require_once('view/User_modify.php');

require_once('view/Expense_add.php');
require_once('view/Expense_list.php');
require_once('view/Expense_modify.php');

require_once('view/Product_list.php');
require_once('view/Product_low_stock.php');
require_once('view/Product_limit_stock.php');
require_once('view/Product_view.php');
require_once('view/Product_add.php');
require_once('view/Product_modify.php');

require_once('view/Sale_add.php');
require_once('view/Sale_list.php');
require_once('view/Sale_modify.php');

require_once('view/Order_add.php');
require_once('view/Order_view.php');
require_once('view/Order_cancel.php');
require_once('view/Order_list_dates.php');
require_once('view/Order_list.php');

require_once('view/Menu_add.php');
require_once('view/Menu_list.php');
require_once('view/Menu_modify.php');

require_once('view/SummaryEarnings_index.php');
require_once('view/SummaryEarningsList.php');
require_once('view/SummaryEarningsListPDF.php');
require_once('view/SummaryColumnsChart.php');
require_once('view/SummaryProducts_index.php');
require_once('view/SummaryProductsList.php');
require_once('view/SummaryProductsListPDF.php');
require_once('view/SummaryPieChart.php');

require_once('view/Config_list.php');

require_once('view/Error_display.php');
require_once('view/AlertaOperacionExitosa.php');
require_once('view/AlertaLoginInvalido.php');
require_once('view/AlertaNoDeleble.php');
require_once('view/AlertaFechasInvalidas.php');
require_once('view/AlertaToken.php');
require_once('view/AlertaOrdenNoPendiente.php');
require_once('view/AlertaStockInsuficiente.php');


if(isset($_GET["action"])) {
    
    if($_GET["action"] == "telegramBot"){
        $method = $_GET["action"];
        ResourceController::getInstance()->$method();
    }
	
	if(is_null($_SESSION['rol'])){
		$current_rol = 3;
	}
	else{
		$current_rol = $_SESSION['rol'];
	}
	$method = $_GET["action"];
	if(in_array($method, $permission_array[$current_rol])){
		ResourceController::getInstance()->$method();
	}
	else{
		echo "no se encuentra habilitado";
	}
}
else{
    ResourceController::getInstance()->home();
}
?>