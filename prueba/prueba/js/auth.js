function animation() {
    const card = document.querySelector('.form-container'); // Selecciona el contenedor del formulario
    if (card) { // Si existe el contenedor
        card.classList.add('animar'); // Agrega la clase para animar
        setTimeout(() => {
            card.classList.remove('animar'); // Quita la clase después de la animación
        }, 700); // Espera 700ms (más que la duración de la animación)
    }
}

function login() {
    const btn = document.getElementById('btn-login'); // Obtiene el botón de login
    if (btn) { // Si existe el botón
        btn.addEventListener('click', function (e) { // Agrega evento click
            e.preventDefault(); // Previene el comportamiento por defecto
            //instaciamos la variable usuarios  y password con el id del elemento html
            const usuario = document.getElementById('usuario').value; // Obtiene el valor del usuario
            const password = document.getElementById('password').value; // Obtiene el valor del password
            const formData = new FormData(document.getElementById("form_login")) // Crea FormData con el formulario
            formData.append("title", "login"); // Agrega el parámetro para login
            //alistamos los datos para enviarlos al login.php
            fetch('../presenters/Login.php', { // Realiza petición fetch al backend
                method: 'POST', // Usa el método POST
                //headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData//`usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}&title='login'`
            })
                .then(response => response.text()) // Convierte la respuesta a texto
                .then(data => { // Maneja la respuesta
                    //validamos la respuesta de php 
                    if (data === 'Error campos vacios') { // Si hay campos vacíos
                        alert_error(data); // Muestra alerta de error
                    } else if (data === "Error en credenciales") { // Si las credenciales son incorrectas
                        alert_error(data); // Muestra alerta de error
                    } else if (data === "ok") { // Si el login es exitoso
                        loadIndex() // Redirige al index
                    }
                })
        });
    }
}

function register() {
    const btn_register = document.getElementById("btn_register"); // Obtiene el botón de registro
    if (btn_register) { // Si existe el botón
        btn_register.addEventListener('click', function (e) { // Agrega evento click
            e.preventDefault(); // Previene el comportamiento por defecto
            const formData = new FormData(document.getElementById("form_register_user")); // Crea FormData con el formulario de registro
            formData.append("title", "create_user"); // Agrega el parámetro para crear usuario
            fetch('../presenters/Login.php', { // Realiza petición fetch al backend
                method: 'POST', // Usa el método POST
                body: formData // Envía el FormData como cuerpo de la petición
            }).then(response => response.text()) // Convierte la respuesta a texto
                .then(data => { // Maneja la respuesta
                    if (data === "No puede haber campos vacios") { // Si hay campos vacíos
                        alert_error(data) // Muestra alerta de error
                    }
                    else if (data === "register") { // Si el registro es externo
                        //document.getElementById("form_register_user").clearForm();
                        alert_success(data, "register") // Muestra alerta de éxito y redirige
                    }
                    else if(data ==="admin_register"){ // Si el registro es de admin
                        alert_success(data , "admin") // Muestra alerta de éxito y cierra modal
                    }
                    else if (data === "Error ya existe el usuario") { // Si el usuario ya existe
                        alert_error(data); // Muestra alerta de error
                    } else if (data === "Error ya existe el email") { // Si el email ya existe
                        alert_error(data); // Muestra alerta de error
                    }
                    else { // Otro error
                        alert_error(data) // Muestra alerta de error
                    }
                }).catch(error => { // Maneja errores de la petición
                    //console.log(error)
                })
        })
    }
}
loadIndex= () =>{
    const contenido = document.getElementById("contenido");
    if(contenido){
        fetch("../views/inicio.html").then(response=>response.text()).then(data=>{
            contenido.innerHTML = data;
            loadJS("../js/load.js" , () =>{
                if(typeof initLoad === "function"){
                    initLoad();
                }
            })
        })
    }
}
loadLogin = () =>{
    const contenido = document.getElementById("contenido");
    if(contenido){
        fetch("../views/auth/login.html").then(response=>response.text()).then(data=>{
            contenido.innerHTML = data;
            loadJS("../js/auth.js",() => {
                    if (typeof initAuth === "function") {
                        initAuth(); // Inicializa los eventos solo cuando el script haya cargado
                    }
                });
        })
    }
}

function alert_success(data, origen) {
    const alert = document.getElementById('alert-success'); // Obtiene el div de alerta de éxito
    if (alert) { // Si existe el div
        alert.innerHTML = "creado"; // Muestra mensaje de éxito
        alert.style.display = 'block'; // Muestra el div

        setTimeout(() => { // Espera 4 segundos
            alert.style.display = 'none' // Oculta el div
            switch (origen) { // Según el origen
                case "register":
                    loadLogin() // carga el login al login
                    break;
                case "admin":
                    const close_modal = document.getElementById("createModal"); // Obtiene el modal de creación
                    if (close_modal) { // Si existe el modal
                        close_modal.style.display = "none"; // Oculta el modal
                        load_usuarios(); // Recarga la lista de usuarios
                    }
                    break;
            }
        }, 4000) // 4 segundos

    }
}

function alert_error(data) {
    //en caso de error de campo vacios se instancia el div elert-error
    const alert = document.getElementById('alert-error'); // Obtiene el div de alerta de error
    //asignamos el texto
    alert.innerHTML = data; // Muestra el mensaje de error
    //se muestra el div ya que estaba oculto
    alert.style.display = 'block' // Muestra el div
    //dejamos 4 segundos visibles y lo ocultamos nuevamente
    setTimeout(() => {
        alert.style.display = 'none' // Oculta el div después de 4 segundos
    }, 4000)

}

window.initAuth = () =>{
    animation(); // Ejecuta la animación al cargar
    login(); // Inicializa el login
    register(); // Inicializa el registro
}

// Ejecuta init cuando el DOM esté listo