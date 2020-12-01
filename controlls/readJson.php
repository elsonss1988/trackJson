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

// Loop  de leitura dos arquivos, leitura a partir do item 2 excluindo as leituras associadas ao diretorios (. e ..)
for($i=2;$i<count($files);$i++){    
    
    $file = file($dirLive."/".$files[$i]);
    //Tratamento das linhas dos arquivos(correção do array $files)
    $fileStr=implode("+",$file);
    $strLine=substr($fileStr, strrpos($fileStr, '{'),strrpos($fileStr, '}')  ); 
    $strTempo=substr($fileStr, strrpos($fileStr, '"tempo"'),strrpos($fileStr, '}') ); 
    /* Filtrando o tempo */
    if (preg_match('/"tempo":(.*?),"log"/', $strTempo, $match) == 1) {
        $valueTime=$match[1];
       //var_dump($strLine);
       //json_encode($strLine,JSON_FORCE_OBJECT);
        $strBr=explode(",",$strLine);
       // var_dump($strBr);
       //print_r($strBr);
        echo json_encode($strBr);
        //echo json_encode($strBr);
        $jsonLine=json_encode($strLine);        
  
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


//recordDB ($arrayUser,$arrayToken);   
?>

