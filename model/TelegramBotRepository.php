<?php

class TelegramBotRepository extends PDORepository {

    private static $instance;
    
    public static function getInstance() {
        
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function addChatId($id) {

    	if ($this->idExist($id)) return 'Ya existe este id';

    	if ($this->queryList("INSERT INTO telegram (chat_id) VALUES (?)", array($id)))
			return true;
		else
			return false;
    }

    public function getIDs() {

        $sql = '';
        return $this->queryAll("SELECT chat_id FROM telegram");
    }

    public function idExist($id) {

        $res = $this->queryList("SELECT * FROM telegram WHERE chat_id = ?", array($id));
        $chat = $res[0]->fetch(PDO::FETCH_ASSOC);
        if($chat['chat_id'] == $id){     
            return true;
        }else{
            return false;
        }
    }
}