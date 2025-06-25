function animation() {
    const card = document.querySelector('.form-container');
    if (card) {
        card.classList.add('animar');
        setTimeout(() => {
            card.classList.remove('animar'); // Opcional: vuelve al estado original después de la animación
        }, 700); // Un poco más que la duración de la transición
    }
}

function login() {
    const btn = document.getElementById('btn-login');
    if (btn) {
        btn.addEventListener('click', function (e) {

            e.preventDefault();
            //instaciamos la variable usuarios  y password con el id del elemento html
            const usuario = document.getElementById('usuario').value;
            const password = document.getElementById('password').value;
            const formData = new FormData(document.getElementById("form_login"))
            formData.append("title" , "login");
            //alistamos los datos para enviarlos al login.php
            fetch('../../presenters/Login.php', {
                method: 'POST',
                //headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData//`usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}&title='login'`
            })
                .then(response => response.text())
                .then(data => {
                    //validamos la respuesta de php 
                    if (data === 'Error campos vacios') {
                        alert_error(data);
                    } else if (data === "Error en credenciales") {
                        alert_error(data);
                    }
                })
        });
    }
}
function register() {
    const btn_register = document.getElementById("btn-register-external");
    if (btn_register) {
        btn_register.addEventListener('click', function (e) {
            e.preventDefault();
            const formData = new FormData(document.getElementById("form_register_user"));
            formData.append("title" , "create_external_user");
            fetch('../../presenters/Login.php',{
                method:'POST',
                body: formData
            }).then(response => response.text())
            .then(data=>{
                if(data === "No puede haber campos vacios"){
                    alert_error(data)
                }
                else if(data === "creado"){
                    //document.getElementById("form_register_user").clearForm();
                    alert_success(data , "register")
                }
                else if(data ==="Error ya existe el usuario"){
                    alert_error(data);
                }else if(data === "Error ya existe el email"){
                    alert_error(data);
                }
                else{
                    alert_error(data)
                }
            }).catch(error=>{
                //console.log(error)
            })
        })
    }
}

function alert_success(data , origen){
    const alert = document.getElementById('alert-success');
    if(alert){
        alert.innerHTML = data;
        alert.style.display = 'block';

        setTimeout(() => {
            alert.style.display = 'none'
            switch(origen){
                case "register":
                    window.location("login.html");
                break;
            }
        } , 4000)
    }
}

function alert_error(data) {
    //en caso de error de campo vacios se instancia el div elert-error
    const alert = document.getElementById('alert-error');
    //asignamos el texto
    alert.innerHTML = data;
    //se muestra el div ya que estaba oculto
    alert.style.display = 'block'
    //dejamos 4 segundos visibles y lo ocultamos nuevamente
    setTimeout(() => {
        alert.style.display = 'none'
    }, 4000)

}

function init() {
    animation();
    login();
    register();
}

document.addEventListener('DOMContentLoaded', init);