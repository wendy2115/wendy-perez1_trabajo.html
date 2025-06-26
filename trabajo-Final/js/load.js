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
                if (nav) {
                    const enlace = document.createElement("a");
                    enlace.href = "/views/auth/login.html";
                    enlace.textContent = "Iniciar Sesión";
                    enlace.className = "header-nav-link";
                    nav.appendChild(enlace)
                }
        })





}
function init() {
    load_header();
}

document.addEventListener('DOMContentLoaded', init())

