

load_citas = () => {
    const table = document.getElementById("table_user_all");

    if (table) {

        fetch('../../presenters/Usuarios.php', {
            method: 'POST',
            body: new URLSearchParams({ 'title': 'user_by_citas' })
        })
            .then(response => response.text())
            .then(data => {
                document.getElementById("table_user_by_citas").innerHTML = data;

            });
    }

}

saveCita = () => {
    const btn = document.getElementById("btn_create_cita");
    if (btn) {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const form = document.getElementById("form_cita");
            const formData = new FormData(form);
            formData.append('title', 'cita_create');
            fetch('../../presenters/Citas.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    //console.log(data);
                    if (data === "success") {
                        alert("Cita creada exitosamente.");
                        load_citas_user();
                        form.reset();
                    }else if(data === "redirect") {
                        window.location.href = "../../views/login.php";
                    } else {
                        alert("Error al crear la cita: " + data);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

    }
}

load_citas_user = () => {
    const citaCad = document.querySelector(".cita-card");
    console.log(citaCad);
    if (citaCad) {
        fetch('../../presenters/Citas.php', {
            method: 'POST',
            body: new URLSearchParams({ 'title': 'citas_user' })
        })
            .then(response => response.text())
            .then(data => {
                if(data === "redirect") {
                    window.location.href = "../../views/auth/login.html";
                    return;
                }
                citaCad.innerHTML = data;
                // Si tienes que asignar eventos, hazlo aquí
                // ejemplo: asignarEventosAEliminarCitas();
            })
            .catch(error => console.error('Error al cargar citas:', error));
    }
}



function init() {
    load_citas();
    load_citas_user();
    saveCita();
}
document.addEventListener('DOMContentLoaded', init())