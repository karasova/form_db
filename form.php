<?php 
    //error_reporting(0);
    include "form_class.php";
    $form = new Form;
    if (!empty($_POST))
    {
	    $form->form_fill();
    }   
?>

<!DOCTYPE html>
<html>
<head>
    <title> Регистрация </title>
</head>
<body>
    <p><b> Форма регистрации на конференцию </b></p>
    <form method = 'POST'>
        <p>Фамилия: <input required name = 'lastname' placeholder = 'Введите фамилию' value = "<?= isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>"><?php echo "\t" . $form->errors['lastname'] ?></p>
        <p>Имя: <input required name = 'firstname' placeholder = 'Введите имя' value = "<?= isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>"><?php echo "\t" . $form->errors['firstname'] ?></p>
        <p>Почта: <input required name = 'email' placeholder = 'Введите e-mail' value = "<?= isset($_POST['email']) ? $_POST['email'] : '' ?>"><?php echo "\t" . $form->errors['email'] ?></p>
        <p>Номер телефона: <input required name = 'phone' placeholder = 'Введите номер' value = "<?= isset($_POST['phone']) ? $_POST['phone'] : '' ?>"><?php echo "\t" . $form->errors['phone'] ?></p>
        <p> Тема конференции:
        <select name="theme" >
            <option value="bus">Бизнес</option>
            <option value="tech">Технологии</option>
            <option value="advert">Реклама и Маркетинг</option>
        </select> </p>
        <p> Оплата:
        <select name="pay" >
            <option value="web">WebMoney</option>
            <option value="yandex">Яндекс.Деньги</option>
            <option value="paypal">PayPal</option>
            <option value="card"> кредитная карта </option>
        </select> </p>
        <p> Получать рассылку о конференции <input type="checkbox" name = "check"> </p>
        <p><button type="submit">Отправить</button></p>
    </form>

   
    
</body>
</html>