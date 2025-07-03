let idUser;

load_citas = () => {
    const table = document.getElementById("table_user_all");

    if (table) {

        fetch('../../presenters/Usuarios.php', {
            method: 'POST',
            body: new URLSearchParams({ 'title': 'user_by_citas' })
        })
            .then(response => response.text())
            .then(data => {
                if(data ==="admin"){
                    alert("Requiere Rol admin");
                }else{
                document.getElementById("table_user_by_citas").innerHTML = data;
                load_citas_by_user();
                }
            });
    }

}

load_citas_by_user= () => {
const table = document.getElementById("table_user_all");
if(table){
    table.addEventListener("click" , (e) => {
        const btn = e.target.closest(".btn-rounded-success")
        if(btn){
            document.getElementById("visual_tabla_citas_by_user").style.display="block";
            idUser = btn.getAttribute("data-id"); 
            load_citas_by_user_table(idUser);
            
        }
    })
}
}
load_citas_by_user_table = (id) => {
    const formData = new FormData()
            formData.append("title" , "get_table_citas_by_user");
            formData.append("idUser" , id);
            fetch("../../presenters/Citas.php",{
                method:"POST",
                body: formData
            }).then(response => response.text())
            .then(data => {
                if(data === "admin"){
                    alert("Requiere rol admin");
                }else {
                    document.getElementById("table_body_citas_by_user").innerHTML = data;
                    edit_cita_modal();
                }
            })
}

event_cita_card = () => {
    const card = document.querySelector(".cita-card").addEventListener("click", (e) => {

        if (e.target.closest("#btn_delete_cita")) {
            delete_cita(e);
        } else if (e.target.closest("#btn_cita_editar")) {
            edita_cita(e);
        }

    });
}

edita_cita = (e) => {
    const btn = e.target.closest("#btn_cita_editar");
    const citaData = JSON.parse(btn.getAttribute("data-cita"));
    const form = document.getElementById("edit_cita_form");
    if (form) {
        form.querySelector("#edit_idCita").value = citaData.idCita;
        form.querySelector("#edit_fecha_cita").value = citaData.fecha_cita;
        form.querySelector("#edit_motivo_cita").value = citaData.motivo_cita;
    } else {
        console.error("Form no encontrado");
    }
    const modal = document.getElementById("editModal");
    if (modal) {
        modal.style.display = "block";
        closeModalEdit();
        if(document.getElementById("btn_updated")){
            document.getElementById("btn_updated").addEventListener("click", (e) => {
                e.preventDefault();
                const formData = new FormData();
                formData.append('idCita', form.querySelector("#edit_idCita").value);
                formData.append('fecha_cita', form.querySelector("#edit_fecha_cita").value);
                formData.append('motivo_cita', form.querySelector("#edit_motivo_cita").value);
                formData.append('title', 'cita_update');
                fetch('../../presenters/Citas.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "success") {
                            alert("Cita actualizada exitosamente.");
                            load_citas_user();
                            modal.style.display = "none";
                            form.reset();
                        } else if (data === "redirect") {
                            window.location.href = "../../views/auth/login.html";
                        } else {
                            alert("Error al actualizar la cita: " + data);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    }
}

closeModalEdit = () => {
    const close = document.getElementById("closeModal");
    if (close) {
        close.addEventListener("click", () => {
            const modal = document.getElementById("editModal");
            if (modal) {
                modal.style.display = "none";
                const form = document.getElementById("edit_cita_form");
                if (form) {
                    form.reset();
                }
            }
        });
    }
}

delete_cita = (e) => {
    const btn = e.target.closest("#btn_delete_cita");
    const citaId = btn.getAttribute("data-id");

    if (confirm("¿Estás seguro de eliminar esta cita?")) {
        fetch('../../presenters/Citas.php', {
            method: 'POST',
            body: new URLSearchParams({ 'title': 'delete_cita', 'idCita': citaId })
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if (data === "success") {
                    alert("Cita eliminada exitosamente.");
                    load_citas_user();
                } else if (data === "redirect") {
                    window.location.href = "../../views/auth/login.html";
                }
                else {
                    alert("Error al eliminar la cita: " + data);
                }
            })
            .catch(error => console.error('Error:', error));
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
                    } else if (data === "redirect") {
                        window.location.href = "../../views/auth/login.html";
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
    if (citaCad) {
        fetch('../../presenters/Citas.php', {
            method: 'POST',
            body: new URLSearchParams({ 'title': 'citas_user' })
        })
            .then(response => response.text())
            .then(data => {
                if (data === "redirect") {
                    window.location.href = "../../views/auth/login.html";
                    return;
                }
                citaCad.innerHTML = data;
                event_cita_card();
            })
            .catch(error => console.error('Error al cargar citas:', error));
    }
}

//administracion
open_modal_cita_admin = (e) => {
    const btn = document.getElementById("btn_open_create_cita");
    if(btn){
        
        btn.addEventListener("click" , () => {
            const create = document.getElementById("create_cita_admin")
            create.innerHTML = "Crear Cita";
            const modal = document.getElementById("modal_cita_create")
            if(modal){
                modal.style.display = "block";
                closeModalAdmin();

                create_cita_admin();
            }
        })
    }
}
create_cita_admin = () => {
    const btn = document.getElementById("create_cita_admin");
    if(btn){
        btn.addEventListener("click" , (e) => {
            e.preventDefault();
            const form  = document.getElementById("form_citas_admin")
            const formData = new FormData(form)
            formData.append("title" , "citas_admin");
            formData.append("idUser" , idUser);
            fetch("../../presenters/Citas.php", {
                method:"POST",
                body: formData
            }).then(response => response.text())
            .then(data => {
                if(data==="redirect"){
                    window.location.href = "/views/auth/login.html";
                }else if(data === "success"){
                    alert("creado con exito");
                    closeModalAdmin();
                    form.reset();
                    load_citas_by_user_table(idUser);
                }else if(data==="admin"){
                    alert("Requiere admin")
                }else if(data ==="Error: Datos incompletos."){
                    alert(data)
                }else if(data==="Error al crear la cita."){
                    alert(data)
                }
            })
        })
    }
}

closeModalAdmin=()=>{
    const close = document.getElementById("close_modal");
    if(close){
        close.addEventListener("click" , ()=>{
            const modal = document.getElementById("modal_cita_create");
            if(modal){
                modal.style.display='none';
                const form = document.getElementById("form_citas_admin");
                if(form){
                    form.reset();
                }
            }
        })
    }
}

edit_cita_modal=()=>{
    const table = document.getElementById("table_body_citas_by_user");
    if(table){
        table.addEventListener("click" , (e) => {
            const btn = e.target.closest("#admin_edit_cita");
            if(btn){
            closeModalAdmin();
            const cita = JSON.parse(btn.getAttribute("data-item"))
            const form = document.getElementById("form_citas_admin")
            if(form){
                document.getElementById("idCita").value = cita.idCita;
                document.getElementById("fecha_cita").value = cita.fecha_cita;
                document.getElementById("motivo_cita").value = cita.motivo_cita;
                const btn = document.getElementById("create_cita_admin");
                btn.innerHTML = "Actualizar" 
                const modal = document.getElementById("modal_cita_create")
                if(modal){
                    modal.style.display = 'block';
                    
                    create_cita_admin();
                }
            }
            }else if(e.target.closest("#admin_delete_cita")){
                const btn = e.target.closest("#admin_delete_cita");
                if(btn){
                    const idCita = btn.getAttribute("data-id")
                    if(confirm('Esta seguro de eliminar')){
                        const formData = new FormData();
                        formData.append("title" , "delete_cita")
                        formData.append("idCita" , idCita)
                        fetch("../../presenters/Citas.php" , {
                            method:'POST',
                            body: formData
                        }).then(response => response.text())
                        .then(data => {
                            if(data === "admin"){
                                alert("Requiere admin")
                            }else if(data ==="success"){
                                alert("eliminado con éxito");
                                load_citas_by_user_table(idUser);
                            }else{
                                alert(data)
                            }
                        })
                    }
                }
            }
        })
        
    }
}

function init() {
    load_citas();
    load_citas_user();
    saveCita();
    open_modal_cita_admin();
}
document.addEventListener('DOMContentLoaded', init)