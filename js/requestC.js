fetch('./controlls/readJson.php',{method:"GET"})
.then(responsestream=>{
    responsestream.json().then(data=>{
    console.log(data);
    });
});


// fetch('./controlls/readJson.php',{method:"GET"})
// .then(responsestream=>{console.log(responsestream)})


// .then(res=>res.text())
// .then(texto=>console.log(texto))

// fetch('./controlls/file.php',{
//       method:"GET",   
// //body: JSON.stringify(data)
// })
// .then(response => response.json()) 
// .then(response => console.log('Success:', JSON.stringify(response)))
// .catch(error => console.error('Error:',error))