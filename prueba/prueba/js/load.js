function load_header() {
    fetch('./header/header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-container').innerHTML = data;

            document.querySelector('nav').addEventListener('click', function (e) {

                //console.log(e)
                if (e.target.tagName === 'A') {
                    const allLinks = document.querySelectorAll('nav a');
                    allLinks.forEach(link => link.classList.remove('active'));

                    e.target.classList.add('active')
                }


            })
            const nav = document.getElementById("nav_header");
            const formData = new FormData();
            formData.append("title", "isLogin");
            fetch("../presenters/Login.php", {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data != "1") {
                        if (nav) {
                            const login = document.createElement("a");
                            login.id = "link_login";
                            login.textContent = "Iniciar Sesión";
                            login.className = "header-nav-link";
                            nav.appendChild(login)
                            const registro = document.createElement("a");
                            registro.id = "link_register";
                            registro.textContent = "Registro";
                            registro.className = "header-nav-link";
                            nav.appendChild(registro);

                        }
                        add_event_contenido()
                    } else {
                        const loginAdmin = new FormData()
                        loginAdmin.append("title", "isAdmin")
                        fetch("../presenters/Login.php", {
                            method: 'POST',
                            body: loginAdmin
                        })
                            .then(response => response.text())
                            .then(data => {
                                if (data == "1") {
                                    if (nav) {
                                        const citas = document.createElement("a");
                                        citas.id = "link_citas_admin";
                                        citas.textContent = "Citas Admin";
                                        citas.className = "header-nav-link";
                                        nav.appendChild(citas);
                                        const noticias = document.createElement("a");
                                        noticias.id = "link_noticias";
                                        noticias.textContent = "Noticias Admin";
                                        noticias.className = "header-nav-link";
                                        nav.appendChild(noticias)
                                        const usuarios = document.createElement("a");
                                        usuarios.id = "link_usuarios";
                                        usuarios.textContent = "Usuarios Admin";
                                        usuarios.className = "header-nav-link";
                                        nav.appendChild(usuarios)
                                        add_event_contenido()
                                    }
                                } else {
                                    if (nav) {
                                        const citaciones = document.createElement("a");
                                        citaciones.id = "link_citaciones";
                                        citaciones.textContent = "Citas";
                                        citaciones.className = "header-nav-link";
                                        nav.appendChild(citaciones)
                                    }
                                }

                            });

                        setTimeout(() => {
                            if (nav) {
                                const perfil = document.createElement("a");
                                perfil.id = "link_perfil";
                                perfil.textContent = "Perfil";
                                perfil.className = "header-nav-link";
                                nav.appendChild(perfil);
                                const cerrarSesion = document.createElement("a");
                                cerrarSesion.href = "";
                                cerrarSesion.id = "cerrar-session"
                                cerrarSesion.textContent = "Cerrar Sesión";
                                cerrarSesion.className = "header-nav-link";
                                nav.appendChild(cerrarSesion);
                                cerrarSe()
                                add_event_contenido()
                            }
                        }, 1500)
                        
                    }
                })



        })

    fetch('inicio.html').then(response => response.text()).then(data => {
        const contenido = document.getElementById("contenido");
        if (contenido) {
            contenido.innerHTML = data

        }
    })

}

add_event_contenido = () => {
    const contenido = document.getElementById("contenido");

    const inicio = document.getElementById("link_inicio")
    if (inicio) {
        inicio.addEventListener('click', () => {
            fetch("inicio.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data;
            })
        })
    }
    const servicios = document.getElementById("link_servicios")
    if (servicios) {
        servicios.addEventListener('click', () => {
            fetch("servicios.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data
                loadCSS('../css/servicios.css')
            })
        })
    }

    const cita = document.getElementById("link_cita");
    if (cita) {
        cita.addEventListener('click', () => {
            fetch("agendar.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data
                // Ejemplo: cargar CSS específico para agendar.html
                loadCSS('../css/agendar.css');
            })
        })
    }
    const contacto = document.getElementById("link_contacto");
    if (contacto) {
        contacto.addEventListener('click', () => {
            fetch("contactos.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data
                // Ejemplo: cargar CSS específico para contactos.html
                loadCSS('../css/contactos.css');
            })
        })
    }

    const login = document.getElementById("link_login");
    if (login) {
        login.addEventListener('click', () => {

            fetch("auth/login.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data
                loadCSS("../css/login.css")
                loadJS("../js/auth.js", () => {
                    if (typeof initAuth === "function") {
                        initAuth(); // Inicializa los eventos solo cuando el script haya cargado
                    }
                })
            })
        })
    }
    const register = document.getElementById("link_register");
    if (register) {
        register.addEventListener('click', () => {
            fetch("auth/register.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data
                loadCSS("../css/login.css")
                loadJS("../js/auth.js", () => {
                    if (typeof initAuth === "function") {
                        initAuth(); // Inicializa los eventos solo cuando el script haya cargado
                    }
                })
            })
        })
    }

    //link perfil
    const link_perfil = document.getElementById("link_perfil");
    if(link_perfil){
        link_perfil.addEventListener('click' , () =>{
            fetch("perfil.html").then(response=>response.text()).then(data=>{
                contenido.innerHTML = data;
                loadJS("../js/perfil.js", () => {
                    if(typeof  initPerfil === "function"){
                        initPerfil();
                    }
                })
            })
        })
    }
    //link citas user
    const link_citaciones = document.getElementById("link_citaciones");
    if(link_citaciones){
        link_citaciones.addEventListener("click", () => {
            fetch("user/citaciones.html").then(response=>response.text()).then(data=>{
                contenido.innerHTML = data;
                loadJS("../js/citas.js" , () => {
                    if(typeof initCitas === "function"){
                        initCitas()
                    }
                })
            })
        })
    }

    const link_noticias = document.getElementById("link_noticias")
    if(link_noticias){
        link_noticias.addEventListener("click" , () => {
            fetch("admin/noticias-administracion.html").then(response=>response.text()).then(data=>{
                contenido.innerHTML = data
                loadJS("../js/noticias.js" , () => {
                    if(typeof initNoticias === "function"){
                        initNoticias();
                    }
                })
            })
        })
    }
    const link_usuarios = document.getElementById("link_usuarios")
    if(link_usuarios){
        link_usuarios.addEventListener("click" , () => {
            fetch("admin/usuarios.html").then(response=> response.text()).then(data=>{
                contenido.innerHTML=data;
                loadJS("../js/table.js" , () =>{
                    if(typeof initUser === "function"){
                        initUser();
                    }
                })
            })
        })
    }

    const link_citas_admin = document.getElementById("link_citas_admin");
    if(link_citas_admin){
        link_citas_admin.addEventListener("click", () => {
            fetch("admin/citas-administracion.html").then(response=>response.text()).then(data=>{
                contenido.innerHTML=data;
                loadJS("../js/citas.js" , () => {
                    if(typeof initCitas === "function"){
                        initCitas();
                    }
                })
            })
        })
    }
}

link_cuerpo = () => {
    const login = document.getElementById("link_login_cuerpo");
    const contenido = document.getElementById("contenido")
    if (login) {
        login.addEventListener("click", () => {
            fetch("auth/login.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data
                loadCSS("../css/login.css")
                loadJS("../js/auth.js", () => {
                    if (typeof initAuth === "function") {
                        initAuth(); // Inicializa los eventos solo cuando el script haya cargado
                    }
                })
            })
        })
    }
    const register = document.getElementById("link_register_cuerpo");
    if (register) {
        register.addEventListener("click", () => {
            fetch("auth/register.html").then(response => response.text()).then(data => {
                contenido.innerHTML = data
                loadCSS("../css/login.css")
                loadJS("../js/auth.js", () => {
                    if (typeof initAuth === "function") {
                        initAuth(); // Inicializa los eventos solo cuando el script haya cargado
                    }
                });
            })
        })
    }
}

function cerrarSe() {
    const btn = document.getElementById("cerrar-session");
    if (btn) {

        btn.addEventListener('click', (e) => {
            e.preventDefault();

            const cerrar = new FormData();
            cerrar.append("title", "logout");
            fetch("../presenters/Login.php", {
                method: "POST",
                body: cerrar
            }).then(response => response.text()).then(data => {
                load_header();
                const contenido = getElementById("contenido");
                fetch("../views/inicio.html").then(response=>response.text()).then(data=>{
                    contenido.innerHTML = data
                })
            });
        })
    }
}

// Función para cargar un archivo CSS dinámicamente
function loadCSS(href) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
}
loadJS = (src, callback) => {
    const script = document.createElement('script');
    script.src = src;
    script.onload = () => callback && callback();
    script.onerror = () => console.error(`Error al cargar ${src}`);
    document.body.appendChild(script);
}

// Ejemplo de uso: cargar un archivo CSS al iniciar
// loadCSS('./styles/header.css'); // Descomenta y ajusta la ruta según sea necesario

window.initLoad = () => {
    load_header();

}



