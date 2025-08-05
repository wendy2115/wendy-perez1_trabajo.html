function load_header() {
    fetch('/views/header/header.html') 
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
            fetch("/../../presenters/Login.php", {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data != "1") {
                        if (nav) {
                            const login = document.createElement("a");
                            login.href = "/views/auth/login.html";
                            login.textContent = "Iniciar Sesión";
                            login.className = "header-nav-link";
                            nav.appendChild(login)
                            const registro = document.createElement("a");
                            registro.href = "/views/auth/register.html";
                            registro.textContent = "Registro";
                            registro.className = "header-nav-link";
                            nav.appendChild(registro);

                        }
                    } else {
                        const loginAdmin = new FormData()
                        loginAdmin.append("title", "isAdmin")
                        fetch("../../presenters/Login.php", {
                            method: 'POST',
                            body: loginAdmin
                        })
                            .then(response => response.text())
                            .then(data => {
                                if (data == "1") {
                                    if (nav) {
                                        const citas = document.createElement("a");
                                        citas.href = "/views/admin/citas-administracion.html";
                                        citas.textContent = "Citas Admin";
                                        citas.className = "header-nav-link";
                                        nav.appendChild(citas);
                                        const noticias = document.createElement("a");
                                        noticias.href = "/views/admin/noticias-administracion.html";
                                        noticias.textContent = "Noticias Admin";
                                        noticias.className = "header-nav-link";
                                        nav.appendChild(noticias)
                                        const usuarios = document.createElement("a");
                                        usuarios.href = "/views/admin/usuarios.html";
                                        usuarios.textContent = "Usuarios Admin";
                                        usuarios.className = "header-nav-link";
                                        nav.appendChild(usuarios)
                                    }
                                } else {
                                    if (nav) {
                                        const citaciones = document.createElement("a");
                                        citaciones.href = "/views/user/citaciones.html";
                                        citaciones.textContent = "Citas";
                                        citaciones.className = "header-nav-link";
                                        nav.appendChild(citaciones)
                                    }
                                }

                            });

                        setTimeout(()=>{
                            if (nav) {
                            const perfil = document.createElement("a");
                            perfil.href = "/views/perfil.html";
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
                        }
                        }, 1500)

                    }
                })



        })





}

function cerrarSe() {
    const btn = document.getElementById("cerrar-session");
    if (btn) {

        btn.addEventListener('click' , (e)=>{
            e.preventDefault();

        const cerrar = new FormData();
        cerrar.append("title", "logout");
        fetch("../../presenters/Login.php", {
            method: "POST",
            body: cerrar
        }).then(response => response.text()).then(data => {
            window.location.href = "/views/auth/login.html";
        });
        })
    }
}

function init() {
    load_header();
    
}

document.addEventListener('DOMContentLoaded', init)

