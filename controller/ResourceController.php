<?php

class ResourceController {
    
    private static $instance;
    private $token = 'bot304487436:AAGW_aoWu1rs2URID8eKedZGCDFatTuRm2I';

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function home(){
        try{
            $fecha = date("Y-m-d");
            $model = ConfigRepository::getInstance()->listAll();
            $menu = MenuRepository::getInstance()->getDailyMenu($fecha);
            $view = new Home();
            $view->show($model, $menu, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }
      
    public function login(){
        $token = $this->getToken();
        $view = new Login();
        $view->show($_SESSION['rol'], $token);
    }

    public function login_user_check(){
        try{
            if ($_POST['token'] == $_SESSION['token']){
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);  
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                $model = UserRepository::getInstance()->login_user($username, $password);
                if ($model[0]){
                    $_SESSION['rol'] = $model[1];
                    $_SESSION['usuario'] = $model[2];
                    $_SESSION['user_id'] = $model[3];
                    ResourceController::getInstance()->home();
                }
                else{
                    $this->alertaLoginInvalido();
                    $this->login();  
                } 
            }else{
                $this->alertaToken();
                $this->login();
            }
               
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function logout(){
        try{
            session_destroy();
            session_start();
            $_SESSION['rol']=null;
            ResourceController::getInstance()->home();
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function order_add(){
        try{
            $token = $this->getToken();
            $fecha= date("Y-m-d");
            $model= ProductRepository::getInstance()->daily_menu_products($fecha);
            $view = new Order_add();
            $view->show($model, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }
    
    public function order_add_check(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $productos_id=$_POST['producto'];
                $cantidades=$_POST['cantidad'];
                foreach ($productos_id as $index => $value){
                    if (filter_var($productos_id[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if (filter_var($cantidades[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                } 
                foreach ($productos_id as $index => $value){
                    $boolean[]=ProductRepository::getInstance()->in_stock($productos_id[$index], $cantidades[$index]);
                }
                if(!(in_array(false, $boolean))){
                    $user_id= $_SESSION['user_id'];
                    $observaciones= $_POST['observaciones'];
                    $fecha_alta= date("Y-m-d H:m:s");
                    $productos= $_POST['producto'];
                    $cantidad= $_POST['cantidad'];
                    $model= OrderRepository::getInstance()->order_add($user_id, $observaciones, $fecha_alta, $productos, $cantidad);
                    $this->alertaOperacionExitosa();
                    $this->order_list_dates();   
                }else{
                    $this->alertaDatosInvalidos();
                }
            }else{
                $this->alertaToken();
                $this->order_add();;
            }

        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }
    public function order_list_dates(){
        try{
            $token = $this->getToken();
            $view = new Order_list_dates();
            $view->show($_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function order_list(){
        try{
            if($_SESSION['rol']==2){
            $user_id= $_SESSION['user_id'];
            if(isset($_POST['desde'])){
                $desde = filter_var($_POST['desde'], FILTER_SANITIZE_STRING);
                $hasta = filter_var($_POST['hasta'], FILTER_SANITIZE_STRING);
            }else{
                $desde = filter_var($_GET['desde'], FILTER_SANITIZE_STRING);
                $hasta = filter_var($_GET['hasta'], FILTER_SANITIZE_STRING);
            }   
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }
            $inicio=$numero_pagina*$tam_pagina;
            $total_paginas = OrderRepository::getInstance()->countOrdersUser($user_id, $desde, $hasta);
            foreach ($total_paginas[0] as $row){
                $total=$row[0];
            }
            $total= ($total / $tam_pagina) - 1;
            $hasta = new DateTime($hasta);
            $hasta->modify('+1 day');
            $hasta = $hasta->format('Y-m-d');
            $orders= OrderRepository::getInstance()->listUser_pages($inicio, $tam_pagina, $desde, $hasta, $user_id);
            }else{
                $desde=null;
                $hasta=null;
                $tam_pagina = ConfigRepository::getInstance()->page_size();
                if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                    $numero_pagina=0;
                }else{
                    if (isset($_GET['anterior'])) {
                        $numero_pagina=$_GET['anterior'] - 1;
                    }
                    else{
                        $numero_pagina=$_GET['siguiente'] + 1;
                    }
                }
                $inicio=$numero_pagina*$tam_pagina;
                $total_paginas = OrderRepository::getInstance()->countOrders();
                foreach ($total_paginas[0] as $row){
                    $total=$row[0];
                } 
                $total= ($total / $tam_pagina) - 1;
                $orders= OrderRepository::getInstance()->listPending_pages($inicio, $tam_pagina);
            }
            $view = new Order_list();
            $users = UserRepository::getInstance()->listAll_users();
            $view->show($total, $numero_pagina, $orders, $desde, $hasta, $users, $_SESSION['rol']);        
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function order_view(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $order = OrderRepository::getInstance()->order_view($_GET['id']);
            $view = new Order_view();
            $view->show($order[0], $order[1], $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function order_cancel_check(){
        try{
            $token = $this->getToken();
            $_SESSION['token']=$token;
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $pedido= OrderRepository::getInstance()->order_cancel_check($_GET['id']);
            $view = new Order_cancel();
            $view->show($pedido, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function order_cancel(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if (!is_null($_POST['descripcion'])){
                    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
                }else{
                    $descripcion="sin motivo";
                }
                if (OrderRepository::getInstance()->isPendingOrder($_GET['id'])){
                    $model= OrderRepository::getInstance()->order_cancel($_GET['id'], $descripcion);
                    $this->alertaOperacionExitosa();
                    if ($_SESSION['rol'] == 2){
                        $this->order_list_dates();
                    }else{
                        $this->order_list();
                    }
                }else{
                    $this->alertaOrdenNoPendiente();
                    $this->order_list();
                }    
            }else{
                $this->alertaToken();
                if ($_SESSION['rol'] == 2){
                    $this->order_list_dates();
                }else{
                    $this->order_list();
                }
            }    
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function order_accept(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            if (OrderRepository::getInstance()->isPendingOrder($_GET['id'])){
                $model= OrderRepository::getInstance()->order_accept($_GET['id']);
                $sale= SaleRepository::getInstance()->sale_order_add($model[0], $model[1]); 
                $this->alertaOperacionExitosa();
                $this->order_list();
            }else{
                $this->alertaOrdenNoPendiente();
                $this->order_list();
            } 
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function menu_add(){
        try{
            $token = $this->getToken();
            $productos = ProductRepository::getInstance()->list_stock();
            $view = new Menu_add();
            $view->show($productos, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function menu_add_check(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);
                $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
                $productos_id = $_POST['producto'];
                if (($fecha == "0000-00-00") || ($fecha == '') || ($productos_id == '')){
                    $this->alertaDatosInvalidos();
                }
                foreach ($productos_id as $index => $value){
                    if (filter_var($productos_id[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                }
                if(count(array_unique($productos_id))<count($productos_id)){
                    $this->alertaDatosInvalidos();
                }else{
                    $model= MenuRepository::getInstance()->menu_add($productos_id, $fecha);
                    $this->alertaOperacionExitosa();
                    $this->menu_list();
                }
            }else{
                $this->alertaToken();
                $this->menu_add();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function menu_delete(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $model= MenuRepository::getInstance()->menu_delete($_GET['id']);
            $this->alertaOperacionExitosa();
            $this -> menu_list();
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function menu_list(){
        try{
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }
            $inicio=$numero_pagina*$tam_pagina;
            $total_paginas = MenuRepository::getInstance()->countMenus();
            foreach ($total_paginas[0] as $row){
                $total=$row[0];
            } 
            $total= ($total / $tam_pagina) - 1;
            $model= MenuRepository::getInstance()->menu_list($inicio, $tam_pagina);
            $view = new Menu_list();
            $view->show($total, $numero_pagina, $model, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function menu_modify_check(){
        try{
            $token = $this->getToken();
            $_SESSION['token'] = $token;
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $productosTotal= ProductRepository::getInstance()->list_stock();
            $resultado= MenuRepository::getInstance()->menu_modify_check($_GET['id']);
            $view = new Menu_modify();
            $productosMenu = $resultado[0];
            $menu = $resultado[1];
            $view->show($productosTotal, $productosMenu, $menu, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function menu_modify(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);
                $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);

                if (filter_var($_POST['habilitado'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if(isset($_POST['producto'])){
                    $productos= $_POST['producto'];
                    if(count(array_unique($productos))<count($productos)){
                        $this->alertaDatosInvalidos();
                    }else{
                        if( (!(MenuRepository::getInstance()->otherEnabledMenuFor($fecha, $_GET['id']))) || ($_POST['habilitado'] == 0) ){
                            $model= MenuRepository::getInstance()->menu_modify($_GET['id'], $productos, $fecha, $_POST['habilitado']);
                            $this->alertaOperacionExitosa();
                            $this -> menu_list();
                        }else{
                            //hay productos repetidos
                            $this->alertaDatosInvalidos();
                        }  
                    }     
                }else{
                    //se eliminaron todos los productos
                    $this->alertaDatosInvalidos();
                }
            }else{
                $this->alertaToken();
                $this->menu_list();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }    

    public function telegramBot() {

        $returnArray = true;
        $rawData     = file_get_contents('php://input');
        $response    = json_decode($rawData, $returnArray);
        $chat_id = intVal($response['message']['chat']['id']);
        $regExp      = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';
        $tmp         = preg_match($regExp, $response['message']['text'], $aResults);
        if (isset($aResults[1])) {
            $cmd        = trim($aResults[1]);
            $cmd_params = trim($aResults[2]);
        } else {
            $cmd        = trim($response['message']['text']);
            $cmd_params = '';
        }

        $res = $this->sendResponse($this->getMessage($cmd, $response));

        if (!TelegramBotRepository::getInstance()->idExist($chat_id)){
            TelegramBotRepository::getInstance()->addChatId($chat_id);
        }

        return;
        
        
    }

    public function broadcast() {

        $users_chatID = TelegramBotRepository::getInstance()->getIDs();

        $response = array();

        $cmd = '/hoy';
        $response['message']['message_id'] = null;

        foreach ($users_chatID as $key => $value) {
            $response['message']['chat']['id'] = $value["chat_id"];
            $this->sendResponse($this->getMessage($cmd, $response));
        }

        $model = ConfigRepository::getInstance()->listAll();
        ResourceController::getInstance()->home();
    }

    private function getMessage($cmd, $response) {

        $message                             = array();
        $message['chat_id']                  = $response['message']['chat']['id'];
        $message['text']                     = null;
        $message['disable_web_page_preview'] = true;
        $message['reply_to_message_id']      = $response['message']['message_id'];
        $message['reply_markup']             = null;

        switch ($cmd) {
            case '/start':
                $message['text'] = 'Bienvenido al bot del buffet de la facultad de informatica ' . $response['message']['from']['first_name'] . PHP_EOL;
                $message['text'] .= 'Puedes utilizar el comando /help para conocer los mensajes que puede enviar';
                $message['reply_to_message_id'] = null;
                break;
            case '/help':
                $message['text'] = 'Los comandos disponibles son:' . PHP_EOL;
                $message['text'] .= '/start para inicializar el bot' . PHP_EOL;
                $message['text'] .= '/hoy para recibir el menú del día' . PHP_EOL;
                $message['text'] .= '/mañana para recibir el menú de mañana' . PHP_EOL;
                $message['text'] .= '/help visualizar la ayuda';
                $message['reply_to_message_id'] = null;
                break;
            case '/hoy':
                $fecha = date("Y-m-d");
                $menu = MenuRepository::getInstance()->getDailyMenu($fecha);

                if (is_null($menu)) 
                    $message['text'] = 'Aun no se encuentra definido el menu del dia.' . PHP_EOL;
                else 
                    $message['text'] = $this->createText($menu);
                break;
            case '/mañana':
                $fecha = date("Y-m-d", strtotime('tomorrow'));
                $menu = MenuRepository::getInstance()->getDailyMenu($fecha);

                if (is_null($menu)) 
                    $message['text'] = 'Aun no se encuentra definido el menu del dia de mañana' . PHP_EOL;
                else 
                    $message['text'] = $this->createText($menu);
                break;
            default:
                $message['text'] = 'Comando inválido.' . PHP_EOL;
                $message['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
                break;
        }

        return $message;
    }

    private function sendResponse($message) {

        $url     = 'https://api.telegram.org/'.$this->token.'/sendMessage';
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($message),
                'ignore_errors' => true
            )
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);

        return preg_match('/Bad Request/', $result);
    }

    private function createText($menu) {

        $text = '';

        foreach ($menu->getProductos() as $key => $value) {
            $text .= 'Producto: ' . $value->getNombre() . ' Marca: ' . $value->getMarca() . ' Precio: $' . $value->getPrecioVentaUnitario() . PHP_EOL;
        }

        $text .= PHP_EOL . 'Precio total: $' . $menu->getTotal();

        return $text;
    }

    public function product_add(){
        try{
            $token = $this->getToken();
            $categorias = CategoryRepository::getInstance()->listAll();
            $view = new Product_add();
            $view->show($categorias, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function product_add_check(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $nombre= filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
                $marca= filter_var($_POST['marca'], FILTER_SANITIZE_STRING);
                if (filter_var($_POST['cantStock'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_POST['cantMinStock'], FILTER_VALIDATE_INT) === false){
                       $this->alertaDatosInvalidos();
                }
                $categoria= filter_var($_POST['categoria'], FILTER_SANITIZE_STRING);
                $proveedor= filter_var($_POST['proveedor'], FILTER_SANITIZE_STRING);
                $options = array('options'=>array('decimal'=>','));  
                if (filter_var($_POST['precioVentaUnidad'], FILTER_VALIDATE_FLOAT, $options) === false){
                    $this->alertaDatosInvalidos();
                }
                $descripcion= filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
                $model= ProductRepository::getInstance()->addProduct($nombre, $marca, $_POST['cantStock'], $_POST['cantMinStock'], $categoria, $proveedor, $_POST['precioVentaUnidad'], $descripcion);
                $this->alertaOperacionExitosa();
                $this->product_list();
            }else{
                $this->alertaToken();
                $this->product_add();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function product_list(){
        try{
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }
            $inicio=$numero_pagina*$tam_pagina;
            $total_paginas = ProductRepository::getInstance()->countProducts();
            foreach ($total_paginas[0] as $row){
                $total=$row[0];
            } 
            $total= ($total / $tam_pagina) - 1;
            $products = ProductRepository::getInstance()->listAll_pages($inicio, $tam_pagina);
            $view = new Product_list();
            $view->show($total, $numero_pagina, $products, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }   
    }

    public function product_low_stock(){
        try{
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }
            $inicio=$numero_pagina*$tam_pagina;
            $total_paginas = ProductRepository::getInstance()->countProductsLowStock();
            foreach ($total_paginas[0] as $row){
                $total=$row[0];
            } 
            $total= ($total / $tam_pagina) - 1;
            $products= ProductRepository::getInstance()->list_low_stock($inicio, $tam_pagina);
            $view = new Product_low_stock();
            $view->show($total, $numero_pagina, $products, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function product_limit_stock(){
        try{
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }
            $inicio=$numero_pagina*$tam_pagina;
            $total_paginas = ProductRepository::getInstance()->countProductsLimitStock();
            foreach ($total_paginas[0] as $row){
                $total=$row[0];
            } 
            $total= ($total / $tam_pagina) - 1;
            $products= ProductRepository::getInstance()->list_limit_stock($inicio, $tam_pagina);
            $view = new Product_limit_stock();
            $view->show($total, $numero_pagina, $products, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function product_view(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $product = ProductRepository::getInstance()->product_view($_GET['id']);
            $categorias = CategoryRepository::getInstance()->listAll();
            $view = new Product_view();
            $view->show($categorias, $product, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }
    
    public function product_delete(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }           
            if ((SaleRepository::getInstance()->anySale($_GET['id'])) && (ExpenseRepository::getInstance()->anyExpense($_GET['id'])) && (OrderRepository::getInstance()->anyOrder($_GET['id'])) && (MenuRepository::getInstance()->anyMenu($_GET['id']))){
                $tam_pagina = ConfigRepository::getInstance()->page_size();
                if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                    $numero_pagina=0;
                }else{
                    if (isset($_GET['anterior'])) {
                        if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                            $this->alertaDatosInvalidos();
                        }
                        $numero_pagina=$_GET['anterior'] - 1;
                    }
                    else{
                        if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                            $this->alertaDatosInvalidos();
                        }
                        $numero_pagina=$_GET['siguiente'] + 1;
                    }
                }
                $inicio=$numero_pagina*$tam_pagina;
                $total_paginas = ProductRepository::getInstance()->countProducts();
                foreach ($total_paginas[0] as $row){
                    $total=$row[0];
                } 
                $total= ($total / $tam_pagina) - 1;
                $model= ProductRepository::getInstance()->product_delete($_GET['id']);
                $product = ProductRepository::getInstance()->listAll_pages($inicio, $tam_pagina);
                $view = new Product_list();
                $view->show($total, $numero_pagina, $product, $_SESSION['rol']);
            }else{
                $this->alertaNoDeleble();
                $this->product_list();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function product_modify(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
                $marca = filter_var($_POST['marca'], FILTER_SANITIZE_STRING);
                if (filter_var($_POST['cantStock'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_POST['cantMinStock'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $categoria = filter_var($_POST['categoria'], FILTER_SANITIZE_STRING);
                $proveedor = filter_var($_POST['proveedor'], FILTER_SANITIZE_STRING);
                if (filter_var($_POST['precioVentaUnidad'], FILTER_VALIDATE_FLOAT) === false){
                    $this->alertaDatosInvalidos();
                }
                $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
                $model= ProductRepository::getInstance()->product_modify($_GET['id'], $nombre, $marca, $_POST['cantStock'], $_POST['cantMinStock'], $categoria, $proveedor, $_POST['precioVentaUnidad'], $descripcion);
                $this->alertaOperacionExitosa();
                $this->product_list();
            }else{
                $this->alertaToken();
                $this->product_list();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function product_modify_check(){
        try{
            $token = $this->getToken();
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $product= ProductRepository::getInstance()->product_modify_check($_GET['id']);
            $categorias = CategoryRepository::getInstance()->listAll();
            $view = new Product_modify();
            $view->show($product, $categorias, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function user_add(){
        $token = $this->getToken();
        $view = new User_add();
        $view->show($_SESSION['rol'], $token);
    }

    public function user_add_check(){
        try{
            if($_SESSION['token'] == $_POST['token']){

                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $contrasena = filter_var($_POST['contrasena'], FILTER_SANITIZE_STRING);
                $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
                $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_STRING);
                if (filter_var($_POST['dni'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_POST['telefono'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $ubicacion = filter_var($_POST['ubicacion'], FILTER_SANITIZE_STRING);
                $rol= 3;
                if(!isset($_POST['habilitado'])){
                    $habilitado=0;
                }else{
                    if (filter_var($_POST['habilitado'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                }
                if(!isset($_POST['departamento'])){
                    $departamento=null;
                }else{
                    $departamento= filter_var($_POST['departamento'], FILTER_SANITIZE_STRING);
                }
                $model= UserRepository::getInstance()->user_add($username, $contrasena,$nombre,$apellido, $_POST['dni'], $_POST['mail'], $_POST['telefono'], $rol, $_POST['habilitado'], $ubicacion, $departamento);
                $this->alertaOperacionExitosa();
                $this->user_add();
            }else{
                $this->alertaToken();
                $this->user_add();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function user_list(){
        $tam_pagina = ConfigRepository::getInstance()->page_size();

        if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
            $numero_pagina=0;
        }else{
            if (isset($_GET['anterior'])) {
                if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $numero_pagina=$_GET['anterior'] - 1;
            }
            else{
                if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $numero_pagina=$_GET['siguiente'] + 1;
            }
        } 
        $inicio=$numero_pagina*$tam_pagina;
        $total_paginas = UserRepository::getInstance()->countUsers();
        foreach ($total_paginas[0] as $row){
            $total=$row[0];
        } 
        $total = ($total / $tam_pagina) - 1;
        $users = UserRepository::getInstance()->listAll($inicio, $tam_pagina);
        $view = new User_list();
        $view->show($_SESSION['rol'], $total, $numero_pagina, $users);
    }

    public function user_delete(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            if (OrderRepository::getInstance()->anyOrderFromUser($_GET['id'])) {
                $model= UserRepository::getInstance()->user_delete($_GET['id']);
                $this->alertaOperacionExitosa();
                $this->user_list();
            }else{
                $this->alertaNoDeleble();
                $this->user_list();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function user_modify(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $clave = filter_var($_POST['contrasena'], FILTER_SANITIZE_STRING);
                $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
                $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_STRING);
                if (filter_var($_POST['dni'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_POST['telefono'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $ubicacion = filter_var($_POST['ubicacion'], FILTER_SANITIZE_STRING);
                if(!isset($_POST['habilitado'])){
                    $habilitado=0;
                }else{
                    if (filter_var($_POST['habilitado'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                }
                if(!isset($_POST['departamento'])){
                    $departamento=null;
                }else{
                    $departamento= filter_var($_POST['departamento'], FILTER_SANITIZE_STRING);
                }
                $model= UserRepository::getInstance()->user_modify($username, $clave, $nombre, $apellido, $_POST['dni'], $_POST['mail'], $_POST['telefono'], $_POST['rol'], $_POST['habilitado'], $ubicacion, $departamento, $_GET['id']);
                $this->alertaOperacionExitosa();
                $this->user_list();
            }else{
                $this->alertaToken();
                $this->user_list();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function user_modify_check(){
        try{
            $token = $this->getToken();
            $_SESSION['token'] = $token;
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $user= UserRepository::getInstance()->user_modify_check($_GET['id']);
            $view = new User_modify();
            $view->show($user, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_add(){
        try{
            $token = $this->getToken();
            $products = ProductRepository::getInstance()->listAll();
            $view = new Expense_add();
            $view->show($products, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_add_check(){
        try{
            if($_POST['token'] == $_SESSION['token']){
                $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
                $proveedor_cuit = $_POST['cuit'];
                if (filter_var($proveedor_cuit, FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $num_factura = $_POST['numFact'];
                if (filter_var($num_factura, FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $productos_id = $_POST['producto'];                 
                $cantidades = $_POST['cantidad'];              
                $precios = $_POST['precio']; 
                foreach ($productos_id as $index => $value){
                    if (filter_var($productos_id[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if (filter_var($cantidades[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if (filter_var($precios[$index], FILTER_VALIDATE_FLOAT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if ( $cantidades[$index] < 0 ){
                        $this->alertaDatosInvalidos();
                    }
                    if ( $precios[$index] < 0 ){
                        $this->alertaDatosInvalidos();
                    }
                }              
                $factura = "uploads/" . $_FILES['imagenFactura']['name'];
                $tmp = $_FILES["imagenFactura"]["tmp_name"];
                $resultado = @move_uploaded_file($tmp, $factura);
                if ($resultado){
                    if(count(array_unique($productos_id))<count($productos_id)){
                        $this->alertaDatosInvalidos();
                    }else{
                        $model= ExpenseRepository::getInstance()->expense_add($fecha, $proveedor_cuit, $factura, $productos_id, $cantidades, $precios, $num_factura);
                        $this->alertaOperacionExitosa();
                        $this->expense_list();
                    }
                }
                else{
                    $this->expense_add();
                }
            }else{
                $this->alertaToken();
                $this->expense_add();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_delete(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $model= ExpenseRepository::getInstance()->expense_delete($_GET['id']);
            $this->alertaOperacionExitosa();
            $this->expense_list();
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_modify(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $compra_id = $_GET['id'];
                if (filter_var($compra_id, FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
                $proveedor_cuit = $_POST['cuit'];
                if (filter_var($proveedor_cuit, FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                $num_factura = $_POST['numFact'];
                if (filter_var($num_factura, FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }                 
                $productos_id = $_POST['producto'];                 
                $cantidades = $_POST['cantidad'];              
                $precios = $_POST['precio'];
                if(count(array_unique($productos_id))<count($productos_id)){
                    $this->alertaDatosInvalidos();  
                }   
                foreach ($productos_id as $index => $value){
                    if (filter_var($productos_id[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if (filter_var($cantidades[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if (filter_var($precios[$index], FILTER_VALIDATE_FLOAT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if ( $cantidades[$index] < 0 ){
                        $this->alertaDatosInvalidos();
                    }
                    if ( $precios[$index] < 0 ){
                        $this->alertaDatosInvalidos();
                    }
                }
                if (($_FILES["imagenFactura"]["error"] == 0)){
                    $factura = "uploads/" . $_FILES['imagenFactura']['name'];
                    $tmp = $_FILES["imagenFactura"]["tmp_name"];
                    $resultado = @move_uploaded_file($tmp, $factura);
                    if ($resultado){
                        $model= ExpenseRepository::getInstance()->expense_modify($compra_id, $num_factura, $factura, $proveedor_cuit, $fecha, $productos_id, $cantidades, $precios);
                        $this->alertaOperacionExitosa();
                        $this->expense_list(); 
                    }
                    else{
                        $this->expense_list();
                    }
                }else{
                    $factura = filter_var($_POST['facturaActual'], FILTER_SANITIZE_STRING);
                    $model= ExpenseRepository::getInstance()->expense_modify($compra_id, $num_factura, $factura, $proveedor_cuit, $fecha, $productos_id, $cantidades, $precios);
                    $this->alertaOperacionExitosa();
                    $this->expense_list();
                }

            }else{
                $this->alertaToken();
                $this->expense_list();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_modify_check(){
        try{
            $token = $this->getToken();
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $productosCompra= ExpenseProductRepository::getInstance()->expense_modify_check($_GET['id']);
            $compra= ExpenseRepository::getInstance()->expense_modify_check($_GET['id']);
            $productosTotal = ProductRepository::getInstance()->listAll();
            $view = new Expense_modify();
            $view->show($compra, $productosCompra, $productosTotal, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_list(){
        try{
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }   
            $inicio=$numero_pagina*$tam_pagina;
            $total_paginas = ExpenseRepository::getInstance()->countExpenses();
            foreach ($total_paginas[0] as $row){
                $total=$row[0];
            } 
            $total= ($total / $tam_pagina) - 1;
            $expenses = ExpenseRepository::getInstance()->listAll($inicio, $tam_pagina);
            $view = new Expense_list();
            $view->show($total, $numero_pagina, $expenses, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_product_add(){
        try{
            $view = new Expense_product_add();
            $view->show($_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function expense_product_list(){
        try{
            $expenses = ExpenseProductRepository::getInstance()->listAll();
            $view = new Expense_list();
            $view->show($expenses, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function sale_list(){
        try{
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }
            $inicio=$numero_pagina*$tam_pagina;
            $total_paginas = SaleRepository::getInstance()->countSales();
            foreach ($total_paginas[0] as $row){
                $total=$row[0];
            } 
            $total= ($total / $tam_pagina) - 1;
            $sales = SaleRepository::getInstance()->listAll($inicio, $tam_pagina);
            $view = new Sale_list();
            $view->show($total, $numero_pagina, $sales, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function sale_add(){
        try{
            $token = $this->getToken();
            $products = ProductRepository::getInstance()->listAll();
            $view = new Sale_add();
            $view->show($products, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function sale_add_check(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $productos_id = $_POST['producto'];
                $cantidades = $_POST['cantidad'];
                foreach ($productos_id as $index => $value){
                    if (filter_var($productos_id[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if (filter_var($cantidades[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if ($cantidades[$index] < 0 ){
                        $this->alertaDatosInvalidos();
                    }
                }
                foreach ($productos_id as $index => $value){
                    $boolean[]=ProductRepository::getInstance()->in_stock($productos_id[$index], $cantidades[$index]);
                }
                if(!(in_array(false, $boolean))){
                    if(count(array_unique($productos_id))<count($productos_id)){
                        $this->alertaDatosInvalidos();
                    }else{
                        $model= SaleRepository::getInstance()->sale_add($productos_id, $cantidades);
                        $this->alertaOperacionExitosa();
                        $this->sale_list();
                    }
                }else{
                    $this->alertaStockInsuficiente();
                    $this->sale_add();
                }
            }else{
                $this->alertaToken();
                $this->sale_add();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function sale_delete(){
        try{
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $model= SaleRepository::getInstance()->sale_delete($_GET['id']);
            $this->alertaOperacionExitosa();
            $this->sale_list();
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function sale_modify(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $venta_id = $_GET['id'];
                $productos_id = $_POST['producto'];
                $cantidades = $_POST['cantidad'];
                if (filter_var($venta_id, FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                foreach ($productos_id as $index => $value){
                    if (filter_var($productos_id[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    if (filter_var($cantidades[$index], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                }
                foreach ($productos_id as $index => $value){
                    $boolean[]=ProductRepository::getInstance()->in_stock($productos_id[$index], $cantidades[$index]);
                }
                if(!(in_array(false, $boolean))){
                    if(count(array_unique($productos_id))<count($productos_id)){
                        $this->alertaDatosInvalidos();
                    }else{
                        $model= SaleRepository::getInstance()->sale_modify($venta_id, $productos_id, $cantidades);
                        $this->alertaOperacionExitosa();
                        $this->sale_list();
                    }
                }else{
                    $this->alertaStockInsuficiente();
                    $this->sale_modify_check();
                }
            }else{
                $this->alertaToken();
                $this->sale_list();
            }
            
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function sale_modify_check(){
        try{
            $token = $this->getToken();
            if (filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $productosVenta= SaleProductRepository::getInstance()->sale_modify_check($_GET['id']);
            $venta= SaleRepository::getInstance()->sale_modify_check($_GET['id']);
            $productosTotal = ProductRepository::getInstance()->listAll();
            $view = new Sale_modify();
            $view->show($venta, $productosVenta, $productosTotal, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function summaryEarnings_index(){
        try{
            $token = $this->getToken();
            $view = new SummaryEarnings_index();
            $view->show($_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }
    
    public function summaryEarnings_index_check(){
        try{
            if($_SESSION['token'] == $_POST['token']){
                $desde = filter_var($_POST['desde'], FILTER_SANITIZE_STRING);
                $hasta = filter_var($_POST['hasta'], FILTER_SANITIZE_STRING);
                if (is_null($desde) || is_null($hasta) || ($desde>$hasta)){
                    $this->alertaFechasInvalidas();
                    $this->summaryEarnings_index();
                }else{
                    $hasta = date('Y-m-d', strtotime($hasta . ' +1 day'));
                    $inicio = new DateTime($desde);
                    $intervalo = new DateInterval('P1D');
                    $fin = new DateTime($hasta);
                    $periodo = new DatePeriod($inicio, $intervalo, $fin);
                    foreach ($periodo as $fecha) {
                        $ganancia = EarningRepository::getInstance()->earning_date($fecha);
                        $ganancias[] = $ganancia;
                    }
                    if ($_POST['opcionElegida'] == 'graficoBarras') {
                        $text = "[";
                        foreach ($ganancias as $ganancia) {
                            $text .= "['" . $ganancia->getFecha() . "', " . $ganancia->getMonto() . "], ";
                        }
                        $text .= "]";
                        $view = new SummaryColumnsChart();
                        $view->show($text, $_SESSION['rol']);
                    }
                    else{
                        $ganancias = array();
                        $desde = new DateTime($desde);
                        $hastaFin = new DateTime($hasta);
                        $interval = date_diff($desde, $hastaFin);
                        $cantDias = $interval->format('%a');
                        $tam_pagina = ConfigRepository::getInstance()->page_size();
                        if ($cantDias >= $tam_pagina) {
                            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                                $numero_pagina=0;
                            }else{
                                if (isset($_GET['anterior'])) {
                                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                                        $this->alertaDatosInvalidos();
                                    }
                                    $numero_pagina=$_GET['anterior'] - 1;
                                }
                                else{
                                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                                        $this->alertaDatosInvalidos();
                                    }
                                    $numero_pagina=$_GET['siguiente'] + 1;
                                }
                            }
                            $desdeInicio = $desde->format('Y-m-d');
                            $hastaFin = $hastaFin->format('Y-m-d');
                            if ($cantDias % $tam_pagina > 0){
                                $total = floor($cantDias / $tam_pagina);
                            }
                            else{
                                $total= floor($cantDias / $tam_pagina) - 1;
                            }
                            $inicio = $desde;
                            $desde = $desde->format('Y-m-d');
                            $hasta = date('Y-m-d', strtotime($desde . ' +' . $tam_pagina . ' day'));
                            $intervalo = new DateInterval('P1D');
                            $fin = new DateTime($hasta);
                            $periodo = new DatePeriod($inicio, $intervalo, $fin);
                            foreach ($periodo as $fecha) {
                                $ganancia = EarningRepository::getInstance()->earning_date($fecha);
                                $ganancias[] = $ganancia;
                            }
                            $view = new SummaryEarningsList();
                            $view->show($desde, $desdeInicio, $hasta, $hastaFin, $total, $numero_pagina, $ganancias, $_SESSION['rol']);
                        }else{
                            $intervalo = new DateInterval('P1D');
                            $periodo = new DatePeriod($desde, $intervalo, $hastaFin);
                            foreach ($periodo as $fecha) {
                                $ganancia = EarningRepository::getInstance()->earning_date($fecha);
                                $ganancias[] = $ganancia;
                            }
                            $view = new SummaryEarningsList();
                            $view->show(0, 0, 0, 0, 0, 0, $ganancias, $_SESSION['rol']);
                        }
                    }
                }
            }else{
                $this->alertaToken();
                $this->summaryEarnings_index();
            }
            
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function summaryEarnings_list(){
        try{
            $desde = filter_var($_GET['desde'], FILTER_SANITIZE_STRING);
            $hasta = filter_var($_GET['hasta'], FILTER_SANITIZE_STRING);
            $hastaFin = filter_var($_GET['hastaFin'], FILTER_SANITIZE_STRING);
            $desdeInicio = filter_var($_GET['desdeInicio'], FILTER_SANITIZE_STRING);
            if (filter_var($_GET['total_paginas'], FILTER_VALIDATE_INT) === false){
                $this->alertaDatosInvalidos();
            }
            $total = $_GET['total_paginas'];
            $tam_pagina = ConfigRepository::getInstance()->page_size();

            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['anterior'] - 1;
                    $hasta = date('Y-m-d', strtotime($desde));
                    $desde = date('Y-m-d', strtotime($desde . ' -' . $tam_pagina . ' day'));
                }
                else{
                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['siguiente'] + 1;
                    $desde = date('Y-m-d', strtotime($desde . ' +' . $tam_pagina . ' day'));
                    $hasta = date('Y-m-d', strtotime($desde . ' +' . $tam_pagina . ' day'));
                }
            }
            $inicio = new DateTime($desde);
            $ganancias = array();
            $intervalo = new DateInterval('P1D');
            if ($total == $numero_pagina) {
                $fin = new DateTime($hastaFin);
            }else{
                $fin = new DateTime($hasta);
            }

            $periodo = new DatePeriod($inicio, $intervalo, $fin);
            foreach ($periodo as $fecha) {
                $ganancia = EarningRepository::getInstance()->earning_date($fecha);
                $ganancias[] = $ganancia;
            }


            $view = new SummaryEarningsList();
            $view->show($desde, $desdeInicio, $hasta, $hastaFin, $total, $numero_pagina, $ganancias, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function summaryEarnings_list_PDF(){
        try{
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            $hastaFin = filter_var($_GET['hastaFin'], FILTER_SANITIZE_STRING);
            $desdeInicio = filter_var($_GET['desdeInicio'], FILTER_SANITIZE_STRING);
            $hasta = date('Y-m-d', strtotime($desdeInicio . ' +' . $tam_pagina . ' day'));
            $total = $_GET['total_paginas'];
            $inicio = new DateTime($desdeInicio);
            $intervalo = new DateInterval('P1D');
            $fin = new DateTime($hastaFin);
            $periodo = new DatePeriod($inicio, $intervalo, $fin);
            foreach ($periodo as $fecha) {
                $ganancia = EarningRepository::getInstance()->earning_date($fecha);
                $ganancias[] = $ganancia;
            }

            $view = new SummaryEarningsListPDF();
            $view->show($desdeInicio, $desdeInicio, $hasta, $hastaFin, $total, $ganancias, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }
    
    public function summaryProducts_index(){
        try{
            $token = $this->getToken();
            $view = new SummaryProducts_index();
            $view->show($_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function summaryProducts_index_check(){
        try{
            if($_POST['token'] == $_SESSION['token']){
                $desde = filter_var($_POST['desde'], FILTER_SANITIZE_STRING);
                $hasta = filter_var($_POST['hasta'], FILTER_SANITIZE_STRING);
                if (is_null($desde) || is_null($hasta) || ($desde > $hasta)){
                    $this->alertaFechasInvalidas();
                    $this->summaryProducts_index();
                }else{
                    $inicio = new DateTime($desde);
                    $fin = new DateTime($hasta);
                    $desde = $inicio->format('Y-m-d H:i:s');
                    $fechaAux = $fin->format('Y-m-d H:i:s');
                    $hasta = date('Y-m-d H:i:s', strtotime($fechaAux . ' +1 day'));
                    $summaryProducts = EarningRepository::getInstance()->products_count_date($desde, $hasta);
                    if ($_POST['opcionElegida'] == 'graficoTorta') {
                        $text = "[";
                        foreach ($summaryProducts as $summary) {
                            $text .= "{name: '" . $summary->getProducto() . "', y: " . $summary->getCantidad() . "}, ";
                        }
                        $text .= "]";
                        $view = new SummaryPieChart();
                        $view->show($text, $_SESSION['rol']);
                    }
                    else{
                        $tam_pagina = ConfigRepository::getInstance()->page_size();

                        if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                            $numero_pagina=0;
                        }else{
                            if (isset($_GET['anterior'])) {
                                if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                                    $this->alertaDatosInvalidos();
                                }
                                $numero_pagina=$_GET['anterior'] - 1;
                            }
                            else{
                                if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                                    $this->alertaDatosInvalidos();
                                }
                                $numero_pagina=$_GET['siguiente'] + 1;
                            }
                        }
                        
                        $inicio=$numero_pagina*$tam_pagina;
                        $listingProducts = EarningRepository::getInstance()->products_count_date_pages($desde, $hasta, $inicio, $tam_pagina);
                        $summaryProducts = EarningRepository::getInstance()->products_count_date($desde, $hasta);
                        $total_tuplas = count($summaryProducts);
                        $total= ($total_tuplas / $tam_pagina) - 1;

                        $view = new SummaryProductsList();
                        $view->show($desde, $hasta, $total, $numero_pagina, $listingProducts, $_SESSION['rol']);
                    }
                } 
            }else{
                $this->alertaToken();
                $this->summaryProducts_index();
            }   
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function summaryProducts_list(){
        try{
            $desde = filter_var($_GET['desde'], FILTER_SANITIZE_STRING);
            $hasta = filter_var($_GET['hasta'], FILTER_SANITIZE_STRING);
            $tam_pagina = ConfigRepository::getInstance()->page_size();
            if((!isset($_GET['anterior'])) && (!isset($_GET['siguiente']))){
                $numero_pagina=0;
            }else{
                if (isset($_GET['anterior'])) {
                    if (filter_var($_GET['anterior'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['anterior'] - 1;
                }
                else{
                    if (filter_var($_GET['siguiente'], FILTER_VALIDATE_INT) === false){
                        $this->alertaDatosInvalidos();
                    }
                    $numero_pagina=$_GET['siguiente'] + 1;
                }
            }
            $inicio=$numero_pagina*$tam_pagina;
            $listingProducts = EarningRepository::getInstance()->products_count_date_pages($desde, $hasta, $inicio, $tam_pagina);
            $summaryProducts = EarningRepository::getInstance()->products_count_date($desde, $hasta);
            $total_tuplas = count($summaryProducts);
            $total= ($total_tuplas / $tam_pagina) - 1;
            $view = new SummaryProductsList();
            $view->show($desde, $hasta, $total, $numero_pagina, $listingProducts, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function summaryProducts_list_PDF(){
        try{
            $desde = filter_var($_GET['desde'], FILTER_SANITIZE_STRING);
            $hasta = filter_var($_GET['hasta'], FILTER_SANITIZE_STRING);
            $listingProducts = EarningRepository::getInstance()->products_count_date($desde, $hasta);
            $view = new SummaryProductsListPDF();
            $view->show($desde, $hasta, $listingProducts, $_SESSION['rol']);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }    
    public function config_list(){
        try{
            $token = $this->getToken();
            $view = new Config_list();
            $model = ConfigRepository::getInstance()->listAll();
            $view->show($model, $_SESSION['rol'], $token);
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function config_modify(){
        try{
            if($_POST['token'] == $_SESSION['token']){
                $titulo = filter_var($_POST['titulo'], FILTER_SANITIZE_STRING);
                $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
                $mensaje = filter_var($_POST['mensaje'], FILTER_SANITIZE_STRING);
                if (filter_var($_POST['paginas'], FILTER_VALIDATE_INT) === false){
                    $this->alertaDatosInvalidos();
                }
                if (filter_var($_POST['contacto'], FILTER_VALIDATE_EMAIL) === false){
                    $this->alertaDatosInvalidos();
                }
                $model = ConfigRepository::getInstance()->config_modify($titulo, $descripcion, $_POST['contacto'], $mensaje, $_POST['paginas']);
                $this->alertaOperacionExitosa();
                ResourceController::getInstance()->home();
            }else{
                $this->alertaToken();
                $this->config_list();
            }
        }
        catch (PDOException $e){
            $this->alertaExcepciones($e);
        }
    }

    public function getToken(){
        $token = md5(uniqid(rand(), TRUE));
        $_SESSION['token'] = $token;
        return $token;
    }

    public function alertaToken(){
        $view = new AlertaToken();
        $view -> show();
    }

    public function alertaOperacionExitosa(){
        $view = new AlertaOperacionExitosa();
        $view -> show();
    }

    public function alertaLoginInvalido(){
        $view = new AlertaLoginInvalido();
        $view -> show();
    }

    public function alertaFechasInvalidas(){
        $view = new AlertaFechasInvalidas();
        $view -> show();
    }

    public function alertaNoDeleble(){
        $view = new AlertaNoDeleble();
        $view -> show();
    }

    public function alertaOrdenNoPendiente(){
        $view = new AlertaOrdenNoPendiente();
        $view -> show();
    }

    public function alertaStockInsuficiente(){
        $view = new alertaStockInsuficiente();
        $view -> show();
    }

    public function alertaExcepciones($e){
        $error="Se ha producido un error en la consulta: " . $e->getMessage();
        $view = new Error_display();
        $view->show($error);
    }

    public function alertaDatosInvalidos(){
        $error="Se ha producido un error en la consulta";
        $view = new Error_display();
        $view->show($error);
        die;
    }
}
