<?php

$returnArray = true;
$rawData = file_get_contents('php://input');
$response = json_decode($rawData, $returnArray);
$id_del_chat = $response['message']['chat']['id'];


// Obtener comando (y sus posibles parametros)
$regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';


$tmp = preg_match($regExp, $response['message']['text'], $aResults);

if (isset($aResults[1])) {
    $cmd = trim($aResults[1]);
    $cmd_params = trim($aResults[2]);
} else {
    $cmd = trim($response['message']['text']);
    $cmd_params = '';
}

$msg = array();
$msg['chat_id'] = $response['message']['chat']['id'];
$msg['text'] = null;
$msg['disable_web_page_preview'] = true;
$msg['reply_to_message_id'] = $response['message']['message_id'];
$msg['reply_markup'] = null;

switch ($cmd) {
    case '/start':
        $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] . PHP_EOL;
        $msg['text'] .= '¿Como puedo ayudarte? Puedes utilizar el comando /help';
        $msg['reply_to_message_id'] = null;
        break;
    case '/help':
        $msg['text']  = 'Los comandos disponibles son estos:' . PHP_EOL;
        $msg['text'] .= '/start Inicializa el bot' . PHP_EOL;;
        $msg['text'] .= '/menú Muestra el menú del día de hoy' . PHP_EOL;;
        $msg['text'] .= '/help Muestra esta ayuda' . PHP_EOL;;
        $msg['reply_to_message_id'] = null;
        break;
    case '/menú':
        $msg['text']  = 'El menú del día es ensalada tropical';
        break;
    default:
        $msg['text']  = 'Lo siento, no es un comando válido.' . PHP_EOL;
        $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
        break;
}

$url = 'https://api.telegram.org/bot304487436:AAGW_aoWu1rs2URID8eKedZGCDFatTuRm2I/sendMessage';

$options = array(
'http' => array(
    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    'method'  => 'POST',
    'content' => http_build_query($msg)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

exit(0);

?>