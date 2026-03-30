<?php
   
  	include "sysfunction.php";
        
   if(isset($_POST['get_option']))
   {
/*   $host = 'localhost';
     $user = 'root';
     $pass = '';
           
     mysql_connect($host, $user, $pass);

     mysql_select_db('demo');
      
*/
     $state = $_POST['get_option'];
     $find=SYS_QUERY("select city from places where state='$state'");
     while($row=SYS_FETCH($find))
     {
       echo "<option>".$row['city']."</option>";
     }
   
     exit;
   }

?>