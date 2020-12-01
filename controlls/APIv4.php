<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

echo ($_SERVER['HTTP_USER_AGENT']);
echo "<br>";
echo ($_SERVER['HTTP_HOST']);
echo "<br>";
echo  'LiveName: '.$_SERVER['REQUEST_URI'];

echo ("<br>");
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$symbian = strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
$windowsphone = strpos($_SERVER['HTTP_USER_AGENT'],"Windows Phone");

if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian || $windowsphone == true) {
    $dispositivo = "mobile";
    $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    if ($iphone == true) {$dispositivo = "iPhone"; }
    if ($android == true) {$dispositivo = "android";}
 }

else { $dispositivo = "Computador";} 

$livePath=explode("/",$_SERVER['REQUEST_URI']);
$dirAPI;
echo $livePath[1];
$dirAPI='./reports/'.$livePath[1];
echo "<br>";

isset($_GET['nome'])?$nome= $_GET['nome']:$nome='Default';// Nome
isset($_GET['idlive'])?$idlive= $_GET['idlive']:$idlive=0;// ID da Live 
isset($_GET['id'])?$id= $_GET['id']:$id='IIIID';// ID do usuário
isset($_GET['email'])?$email= $_GET['email']:$email='default@email.com';// Email
isset($_GET['u_tempo'])?$log= $_GET['u_tempo']:$log=0;// Nome
isset($_GET['token'])?$token= $_GET['token']:$token=1;// Nome
isset($_GET['liveName'])?$liveName= $_GET['LiveName']:$LiveName=$livePath[1];// Nom
isset($_GET['u_tempo_session'])?$tempo= $_GET['u_tempo_session']:$tempo=0;// Nome



echo $dispositivo;

$obj= new stdClass;
$obj->nome=$nome;
$obj->email=$email;
$obj->idlive=$idlive;
$obj->tempo=$tempo;
$obj->log=$log;
$obj->dispositivo=$dispositivo;
$obj->liveName=$LiveName;
$dadaJSON=json_encode($obj);
echo "./".$livePath[1];
if(is_dir('./reports/'.$livePath[1]))
{
    echo " A Pasta Existe";
}else{
    echo " A Pasta Não Existe";
    mkdir('./reports/'.$livePath[1]);    
}

#$dirAPI='./reports/'.$livePath[1];

try {
    $fileJson = fopen($dirAPI."/".$id."_".$token.".json", "a+") or die("Unable to open file!");
    fwrite($fileJson,(json_encode(utf8ize($obj))));
    if (!$fileJson) {
        throw new Exception("Could not open the file!");
    }    
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $fileLog = fopen("log.txt", "a+") or die("Unable to open file!");
        fwrite($fileLog,"O ID".$id." falhou no envio ás ".date("h:i:sa")."\n");
        fclose($fileLog);
    }fclose($fileJson);

// normalize json
function utf8ize($mixed){
    if(is_array($mixed)){
        foreach($mixed as $key=>$value){
            $mixed[$key]=utf8ize($value);
        }
        }elseif(is_string($mixed)){
            return mb_convert_encoding($mixed,"UTF-8","UTF-8");
        }
        return $mixed;
    }// endnormalize json

?>