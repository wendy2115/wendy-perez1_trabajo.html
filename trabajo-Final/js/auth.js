document.getElementById('btn-login').addEventListener('click', function(e){

    e.preventDefault();
    //instaciamos la variable usuarios  y password con el id del elemento html
    const usuario = document.getElementById('usuario').value;
    const password = document.getElementById('password').value;
    //alistamos los datos para enviarlos al login.php
    fetch('../../presenters/Login.php' , {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}&title='login'`
    })
    .then(response => response.text())
    .then(data =>{
        //validamos la respuesta de php 
        if(data ==='error campos vacios'){
            //en caso de error de campo vacios se instancia el div elert-error
            const alert = document.getElementById('alert-error');
            //asignamos el texto
            alert.innerHTML="No puede tener campos vacios";
            //se muestra el div ya que estaba oculto
            alert.style.display = 'block'
            //dejamos 4 segundos visibles y lo ocultamos nuevamente
            setTimeout(()=>{
                alert.style.display = 'none'
            },4000)
        }
    })
});