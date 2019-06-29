<?php
include 'Flash.php';

class Form {
    private $lastname;
    private $name;
    private $email;
    private $phone;
    private $theme;
    private $pay;
    private $check;
    public $dateCreate;
    private $ipaddr;
    public $status;
    private $dateDelete;
    private $dateUpdate;

    public $errors = array();

    public $table= 'participants';
	public $table2= 'subjects';
	public $table3= 'payments';

    private function check_and_fill(){        

        if (empty($_POST['check'])){
            $this->check = 'off';
        }
        else 
            $this->check = $_POST['check'];

        if (preg_match("/[А-Я][а-я]+$/", $_POST['lastname']) != null){
            $this->lastname = $_POST['lastname'];
            $this->lastname = str_replace("|", "", $this->lastname);
        }
        else 
            $this->errors ['lastname'] = 'Введите фамилию русскими буквами, первая буква заглавная';

        if (preg_match("/[А-Я][а-я]+$/", $_POST['firstname']) != null){
            $this->firstname = $_POST['firstname'];
            $this->firstname = str_replace("|", "", $this->firstname);
        }
        else 
            $this->errors ['firstname'] = 'Введите имя русскими буквами, первая буква заглавная';

        if (preg_match("/[a-z0-9]+@[a-z]+\.[a-z]+$/i", $_POST['email']) != null){
            $this->email = $_POST['email'];
            $this->email = str_replace("|", "", $this->email);
        }
        else 
            $this->errors ['email'] = 'Некорректно введен адрес электронной почты!'; 
            
        if (preg_match("/(\+7|8)( ?)\d{3}( ?)\d{3}(-?)\d{2}(-?)\d{2}$/", $_POST['phone']) != null){
            $this->phone = $_POST['phone'];
            $this->phone = preg_replace("/(\+7|8)(?: ?)(\d{3})(?: ?)(\d{3})(?:\-?)(\d{2})(?:\-?)(\d{2})$/", "$1 $2 $3-$4-$5", $this->phone);
        }
        else 
            $this->errors ['phone'] = 'Некорректно введен номер телефона!';  
        
        $this->theme = $_POST['theme'];
        $this->pay = $_POST['pay'];
        

        $this->dateCreate = date('Y-m-d-H-i-s');
        $this->dateUpdate = date('Y-m-d-H-i-s');
        $this->ipaddr = $_SERVER['REMOTE_ADDR'];
        $this->status = 'active';

    }

    public static function get_pdo(){
		$_pdo;
        if (empty($_pdo)) {
            $_pdo = new PDO('mysql:host=localhost; dbname=form', 'root', ''); 
        }
        return $_pdo;
    }

    public function write_to_db(){
        switch ($this->theme) {
            case 'bus':
                $t = 1;
                break;
            case 'tech':
                $t = 2;
                break;
            case 'advert':
                $t = 3;
                break;
        }

        switch ($this->pay){
            case 'web':
                $p = 1;
                break;
            case 'yandex':
                $p = 2;
                break;
            case 'paypal':
                $p = 3;
                break;
            case 'card':
                $p = 4;
                break;
        }

        $sql = static::get_pdo()->prepare('INSERT INTO `' . $this->table . '` (`name`, `lastname`, `email`, `phone`, `subject_id`, `payment_id`, `created_at`, 
            `updated_at`, `deleted_at`) VALUES (?,?,?,?,?,?,?,?,?);');
        $sql->execute(array($this->name, $this->lastname, $this->email, $this->phone, $t, $p, $this->dateCreate, $this->dateUpdate, $this->dateDelete));
        //return $sql->rowCount() === 1;
    }

    public function form_fill() {
        $this->check_and_fill();

        $message = new Flash;
        $message->set('<h2>Ваша заявка принята!</h2>');

        if (empty($this->errors)){
            $this->write_to_db();
            echo $message->get();
            //header('Location: form_str\success.php');
            exit;    
        }   
        
    } 

    public function read_from_db(){
        $sql = static::get_pdo()->prepare('SELECT t1.id, t1.name, lastname, email, phone, t2.sub_name, t3.pay_name, created_at, updated_at, deleted_at FROM `' . $this->table . '` t1,`' . $this->table2 . '` t2,`' . $this->table3 . '` t3 WHERE t1.subject_id = t2.id AND t1.payment_id = t3.id AND `deleted_at` is NULL;');
        $sql->execute();

        $objects = [];

        while ($object = $sql->fetchObject(static::class)) {
            $str = $object->id . "|" . $object->name . "|" . $object->lastname . "|" . $object->email . "|" . $object->phone . "|" . $object->sub_name . "|" . $object->pay_name . "|" . $object->created_at . "|" . $object->updated_at;
            $res = preg_replace("/ /" , "" , $str);
            echo "<input type='checkbox' name='deletes[]' value=". $res .">". $str . "<br>";
            $objects[] = $object;
        }

        return $objects;
    }

    public function delete(){
        if (empty($_POST['deletes'])){
            echo "<h2> Вы ничего не выбрали</h2>";
        }
        else {
            $deletefiles = $_POST['deletes'];
            $files = array();

            foreach ($deletefiles as $key){
                $file = explode('|', $key);
                array_push($files, $file[0]);
            }

            foreach ($files as $key){
                $sql = $this->get_pdo()->prepare('UPDATE `'.$this->table.'` SET `deleted_at` = ? WHERE `id` = ?;');
                $sql->execute(array(date('Y-m-d-H-i-s'),$key)); 
            }

            echo "<h2>Файлы удалены</h2>";
        }
    }
}
?>