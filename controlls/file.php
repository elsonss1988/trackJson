<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to_function = $_POST['funcao'];
}else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $to_function = $_GET['funcao'];
}else {
    $to_function = $_PUT['funcao'];
}



if($to_function ==  "consultando"){
  print "Hello";
}else{
  print "Hallo";
}
?>