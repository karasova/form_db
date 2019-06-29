<!DOCTYPE html>
<html>
<head>
    <title> Admin </title>
</head>
<body>
    <?php
        error_reporting(0);
        
        include "delete.php";
    ?>
    <form method = "POST">
        <table border = '1'>
            <thead>
                <tr>
                    <th> № </th>
                    <th> Дата создания</th>
                    <th> Удалить </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                    foreach ($files as $key){
                        if ($key->status === 'active'){
                            $table .= "<tr><td>" . $i . "</td><td>". $key->date . "</td><td> <input type = 'checkbox' name = 'deletes[]' value='" . $key->date  . "'></td></tr>";
                            $i++;
                        }
                    }            
                    echo $table;   
                
                ?>
            </tbody>


        </table>
        <p><button type="submit">Отправить</button></p>
     </form>
</body>
</html>
