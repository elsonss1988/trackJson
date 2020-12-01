<?php
# Variaveis
 $j=0;
 $logs=[];
 $campos=[];
 $arrayUser=[];
 $idfile='';
 $idcount=0;
 $file=[];
 $arrayToken=[];
 $offset;
 $dir = './reports';
 $livePath=explode("/",$_SERVER['REQUEST_URI']);
 $dirLive='./reports/'.$livePath[1];
 $lives = scandir($dir); // show all files in directory
 $files = scandir($dirLive); // show all files in directory

###############<<<<<< DEBUGADOR l############################
 echo 'Evento Atual: '.ucfirst($livePath[1].'*');
 echo '<br>';
 echo ('Existem: '.(count($lives)-2).' Lives');
 echo '<br>';

echo "<h2>Lista de Eventos</h2> ";
for($i=2;$i<count($lives);$i++){
    echo  ucfirst($lives[$i]);
    echo '<br>'; }

 echo "<h2>Lista de Arquivos</h2> ";
 for($i=2;$i<count($files);$i++){
     echo  ucfirst($files[$i]);
     echo '<br>';
  }
echo '<br>';
echo "<strong> xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx </strong>";
echo '<br>';
###############>>>>> DEBUGADOR l############################

function strToJson($input){
    $subs=array();
    $length = strlen($input);
    for($i=0; $i<$length;$i++){
        for($j=$i;$j<$length;$j++){
            $subs[]=substr($input,$i,$j);
        }
    }
    return $subs;
}




// Loop  de leitura dos arquivos, leitura a partir do item 2 excluindo as leituras associadas ao diretorios (. e ..)
for($i=2;$i<count($files);$i++){    
    
    $file = file($dirLive."/".$files[$i]);
    //Tratamento das linhas dos arquivos(correção do array $files)
    $fileStr=implode("+",$file)."<br>";
    print_r ($fileStr);
    # echo '<br>';
    $strLine=substr($fileStr, strrpos($fileStr, '{'),strrpos($fileStr, '}')  ); 
    $strTempo=substr($fileStr, strrpos($fileStr, '"tempo"'),strrpos($fileStr, '}') ); 
    //print_r($strTempo);
    echo '<br>';
    /* Filtrando o tempo */
    if (preg_match('/"tempo":(.*?),"log"/', $strTempo, $match) == 1) {
        $valueTime=$match[1];
        echo '<br>';
        // var_dump(json_decode($strLine, true));
        print_r($strLine);
        echo '<br>';
        $strBr=explode(",",$strLine);
        //print_r($strBr);
        echo '<br>';
        $jsonLine=json_encode($strLine);
        // var_dump($jsonLine);
        // var_dump($json2);
        //echo $jsonLine->name;
        //print_r($match);
        echo '<br>';
    } 
    // $subs[] = strToJson($strLine);
    // print_r($subs);  
       # handle files arrays
    $dadoArray=explode("_",$files[$i]);
    

    if ($idfile==$dadoArray[0]){
        # incrementação para sessões maiores que 1.
        $idcount++;
        ${$dadoArray[0]}->idcount++;
        ${$dadoArray[0]}->lasttoken=trim(str_replace(array('.json','<br>'), '',$dadoArray[1]));
        ${$dadoArray[0]}->tempo=$valueTime;
        ${$dadoArray[0]}->log=$valueTime;      
    }

    else{
        # definição inicial
        $idcount=1;
        $j++;
        $idfile=$dadoArray[0];
        ${$dadoArray[0]}= new stdClass;
        ${$dadoArray[0]}->idkey=$j;
        ${$dadoArray[0]}->idfile= $idfile;
        ${$dadoArray[0]}->idcount=$idcount;
        ${$dadoArray[0]}->lasttoken=trim(str_replace(array('.json','<br>'), '',$dadoArray[1]));
        ${$dadoArray[0]}->tempo=$valueTime;
        ${$dadoArray[0]}->log=$valueTime;
        array_push($arrayUser,${$dadoArray[0]});        
    }    

    $objToken= new stdClass;
    $objToken->idfile=$dadoArray[0];
    $objToken->idcount=$i;
    $objToken->idToken=trim(str_replace(array('.json','<br>'), '',$dadoArray[1]));
    $objToken->tempo=$valueTime;    
    array_push($arrayToken, $objToken);
}

echo "<h1>Informações do Arquivo JSON</h1>";
# visualização dos dados
echo "<br>-------------------------------------------------------<br>";
echo "<h3>Informações dos Participantes</h3>";
for($i=0;$i<count($arrayUser);$i++){
    //echo "Key:    ".str_pad($arrayUser[$i]->idkey,3, '0', STR_PAD_LEFT)." ===> ";
    echo "ID:     ".str_pad($arrayUser[$i]->idfile, 3, '0', STR_PAD_LEFT)." ===> ";
    echo "Token:  ".str_pad($arrayUser[$i]->lasttoken, 3, '0', STR_PAD_LEFT)." ===> ";
    echo "Sessões:".str_pad($arrayUser[$i]->idcount, 3, '0', STR_PAD_LEFT)." ===>";
    echo "Tempo:  ".str_pad($arrayUser[$i]->tempo, 3, '0', STR_PAD_LEFT)."<br>";

}
echo "<br>";

echo "<br>-------------------------------------------------------<br>";
echo "<h3>Informações das Sessões</h3>";
for($i=0;$i<count($arrayToken);$i++){
    echo "FileID:    ".str_pad($arrayToken[$i]->idfile,3, '0', STR_PAD_LEFT)." ===> ";
    echo "CountFile: ".str_pad($arrayToken[$i]->idcount,3, '0', STR_PAD_LEFT)." ===> ";
    echo "Token:     ".str_pad($arrayToken[$i]->idToken, 3, '0', STR_PAD_LEFT)." ===> ";
    echo "Log:       ".str_pad($arrayToken[$i]->tempo, 3, '0', STR_PAD_LEFT)."<br>";    
}
echo "<br>";

echo "<strong>recordDB</strong>";

//recordDB ($arrayUser,$arrayToken);   
?>

<?php

function recordDB($arrayUser,$arrayToken){
    $sqlQueryTime='';
    $sqlQueryLog='';
//function recordDB($campo,$valor,$id,$token){
    for($i=0;$i<count($arrayUser);$i++){
        $tempo=$arrayUser[$i]->tempo;
        $idfile=$arrayUser[$i]->idfile;
        echo "<br>";
        echo "UPDATE `TABLE9` SET tempo_session='$tempo' WHERE id=$idfile";
        $sqlQueryTime.= "update`TABLE9` set tempo_session='$tempo' where id=$idfile;";
    }
    for($i=0;$i<count($arrayToken);$i++){
        $log=$arrayToken[$i]->tempo;
        $idfile=$arrayToken[$i]->idfile;
        $idToken=$arrayToken[$i]->idToken;
        echo "<br>";
        echo "UPDATE `TABLE10` SET tempo_session = '$log' WHERE id_user = '$idfile' && token  ='$idToken'";
        $sqlQueryLog.="UPDATE `TABLE10` SET tempo_session = '$log' WHERE id_user = '$idfile' && token  ='$idToken';";
    }
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    $sql=$sqlQueryTime.$sqlQueryLog;
    echo $sql;

    // include connection definition
    include("conexao.php");
    //Connection and Error MSG
    $con= new mysqli($host,$user,$pass,$database);
    if ($result = $con -> query($sql)) {
        // Free result set
        $result -> free_result();
      }
    
    // Execute multi query
    // if (mysqli_multi_query($con, $sql)) {
    //     do {
    //     // Store first result set
    //     if ($result = mysqli_store_result($con)) {
    //         while ($row = mysqli_fetch_row($result)) {
    //         printf("%s\n", $row[0]);
    //         }
    //         mysqli_free_result($result);
    //     }
    //     // if there are more result-sets, the print a divider
    //     if (mysqli_more_results($con)) {
    //         printf("-------------\n");
    //     }
    //     //Prepare next result set
    //     } while (mysqli_next_result($con));
    // }
    
    mysqli_close($con);
}

?>