console.log("request Ok!#")
$.ajax({
    url:"./controlls/file.php",
    type:"GET",
    data:{
        'funcao':'consultando',
    },    
    sucess:function(data){
            console.log('sucesso',data);
    },
    error:function(data){
        console.log('Error ',data)
    },
   
})

