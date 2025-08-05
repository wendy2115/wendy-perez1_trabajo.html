let idUser; // Variable global para almacenar el id del usuario seleccionado

// Función para cargar la tabla de usuarios con citas
load_citas = () => {
    const table = document.getElementById("table_user_all"); // Obtiene la tabla de usuarios

    if (table) { // Si existe la tabla
        fetch('../presenters/Usuarios.php', { // Hace petición al backend
            method: 'POST', // Método POST
            body: new URLSearchParams({ 'title': 'user_by_citas' }) // Envía el parámetro para obtener usuarios por citas
        })
            .then(response => response.text()) // Convierte la respuesta a texto
            .then(data => {
                if(data ==="admin"){ // Si requiere rol admin
                    alert("Requiere Rol admin");
                }else{
                    document.getElementById("table_user_by_citas").innerHTML = data; // Inserta los datos en la tabla
                    load_citas_by_user(); // Inicializa el evento para cargar citas por usuario
                }
            });
    }

}

// Agrega evento para cargar citas de un usuario al hacer click en la tabla
load_citas_by_user= () => {
    const table = document.getElementById("table_user_all"); // Obtiene la tabla de usuarios
    if(table){
        table.addEventListener("click" , (e) => { // Agrega evento click a la tabla
            const btn = e.target.closest(".btn-rounded-success") // Busca el botón de acción
            if(btn){
                document.getElementById("visual_tabla_citas_by_user").style.display="block"; // Muestra la tabla de citas por usuario
                idUser = btn.getAttribute("data-id"); // Obtiene el id del usuario
                load_citas_by_user_table(idUser); // Carga las citas del usuario
            }
        })
    }
}

// Carga la tabla de citas de un usuario específico
load_citas_by_user_table = (id) => {
    const formData = new FormData() // Crea un FormData
    formData.append("title" , "get_table_citas_by_user"); // Agrega el parámetro de acción
    formData.append("idUser" , id); // Agrega el id del usuario
    fetch("../presenters/Citas.php",{
        method:"POST", // Método POST
        body: formData // Envía el FormData
    }).then(response => response.text()) // Convierte la respuesta a texto
    .then(data => {
        if(data === "admin"){ // Si requiere admin
            alert("Requiere rol admin");
        }else {
            document.getElementById("table_body_citas_by_user").innerHTML = data; // Inserta las citas en la tabla
            edit_cita_modal(); // Inicializa eventos de edición/eliminación
        }
    })
}

// Agrega eventos a las tarjetas de cita para editar o eliminar
event_cita_card = () => {
    const card = document.querySelector(".cita-card");
    if(card){
        card.replaceWith(card.cloneNode(true))
        const newCard = document.querySelector(".cita-card")
        newCard.addEventListener("click", (e) => { // Evento click en la tarjeta
        if (e.target.closest("#btn_delete_cita")) { // Si se hace click en eliminar
            delete_cita(e); // Llama a eliminar cita
        } else if (e.target.closest("#btn_cita_editar")) { // Si se hace click en editar
            edita_cita(e); // Llama a editar cita
        }
    });
    }
}

// Abre el modal de edición de cita y carga los datos en el formulario
edita_cita = (e) => {
    const btn = e.target.closest("#btn_cita_editar"); // Obtiene el botón de editar
    const citaData = JSON.parse(btn.getAttribute("data-cita")); // Obtiene los datos de la cita
    const form = document.getElementById("edit_cita_form"); // Obtiene el formulario de edición
    if (form) {
        form.querySelector("#edit_idCita").value = citaData.idCita; // Llena el id de la cita
        form.querySelector("#edit_fecha_cita").value = citaData.fecha_cita; // Llena la fecha
        form.querySelector("#edit_motivo_cita").value = citaData.motivo_cita; // Llena el motivo
    } else {
        console.error("Form no encontrado");
    }
    const modal = document.getElementById("editModal"); // Obtiene el modal de edición
    if (modal) {
        modal.style.display = "block"; // Muestra el modal
        closeModalEdit(); // Inicializa evento para cerrar modal
        const btn_edit = document.getElementById("btn_updated");
        if(btn_edit){
            btn_edit.replaceWith(btn_edit.cloneNode(true));
            const newbtn_edi = document.getElementById("btn_updated")
            newbtn_edi.addEventListener("click", (e) => { // Evento para actualizar cita
                e.preventDefault();
                const formData = new FormData();
                formData.append('idCita', form.querySelector("#edit_idCita").value);
                formData.append('fecha_cita', form.querySelector("#edit_fecha_cita").value);
                formData.append('motivo_cita', form.querySelector("#edit_motivo_cita").value);
                formData.append('title', 'cita_update');
                fetch('../presenters/Citas.php', {
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
                            //window.location.href = "../../views/auth/login.html";
                        } else {
                            alert("Error al actualizar la cita: " + data);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    }
}

// Cierra el modal de edición de cita y resetea el formulario
closeModalEdit = () => {
    const close = document.getElementById("closeModal"); // Obtiene el botón de cerrar modal
    if (close) {
        close.addEventListener("click", () => { // Evento click para cerrar
            const modal = document.getElementById("editModal"); // Obtiene el modal
            if (modal) {
                modal.style.display = "none"; // Oculta el modal
                const form = document.getElementById("edit_cita_form"); // Obtiene el formulario
                if (form) {
                    form.reset(); // Resetea el formulario
                }
            }
        });
    }
}

// Elimina una cita seleccionada
delete_cita = (e) => {
    const btn = e.target.closest("#btn_delete_cita"); // Obtiene el botón de eliminar
    const citaId = btn.getAttribute("data-id"); // Obtiene el id de la cita

    if (confirm("¿Estás seguro de eliminar esta cita?")) { // Confirma la eliminación
        fetch('../presenters/Citas.php', {
            method: 'POST',
            body: new URLSearchParams({ 'title': 'delete_cita', 'idCita': citaId }) // Envía los datos
        })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    alert("Cita eliminada exitosamente.");
                    load_citas_user();
                } else if (data === "redirect") {
                    //window.location.href = "../../views/auth/login.html";
                }
                else {
                    alert("Error al eliminar la cita: " + data);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

// Guarda una nueva cita desde el formulario
saveCita = () => {
    const btn = document.getElementById("btn_create_cita"); // Obtiene el botón de crear cita
    if (btn) {
        btn.addEventListener("click", (e) => { // Evento click para crear cita
            e.preventDefault();
            const form = document.getElementById("form_cita"); // Obtiene el formulario
            const formData = new FormData(form); // Crea FormData con los datos del formulario
            formData.append('title', 'cita_create'); // Agrega el parámetro de acción
            fetch('../presenters/Citas.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then (data => {
                    //console.log(data);
                    if (data === "success") {
                        alert("Cita creada exitosamente.");
                        load_citas_user();
                        form.reset();
                    } else if (data === "redirect") {
                        //window.location.href = "../../views/auth/login.html";
                    } else {
                        alert("Error al crear la cita: " + data);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

    }
}

// Carga las citas del usuario actual y agrega eventos a las tarjetas
load_citas_user = () => {
    const citaCad = document.querySelector(".cita-card"); // Obtiene el contenedor de las citas
    if (citaCad) {
        fetch('../presenters/Citas.php', {
            method: 'POST',
            body: new URLSearchParams({ 'title': 'citas_user' }) // Envía el parámetro para obtener citas del usuario
        })
            .then(response => response.text())
            .then(data => {
                if (data === "redirect") {
                    //window.location.href = "../../views/auth/login.html";
                    
                }
                citaCad.innerHTML = data; // Inserta las citas en el contenedor
                event_cita_card(); // Inicializa eventos de edición/eliminación
            })
            .catch(error => console.error('Error al cargar citas:', error));
    }
}

//administracion

// Abre el modal para crear cita como admin
open_modal_cita_admin = (e) => {
    const btn = document.getElementById("btn_open_create_cita"); // Obtiene el botón para abrir el modal
    if(btn){
        btn.addEventListener("click" , () => { // Evento click para abrir modal
            const create = document.getElementById("create_cita_admin") // Obtiene el botón de crear cita en el modal
            create.innerHTML = "Crear Cita"; // Cambia el texto del botón
            const modal = document.getElementById("modal_cita_create") // Obtiene el modal
            if(modal){
                modal.style.display = "block"; // Muestra el modal
                closeModalAdmin(); // Inicializa evento para cerrar modal
                create_cita_admin(); // Inicializa evento para crear cita
            }
        })
    }
}

// Crea o actualiza una cita como admin
create_cita_admin = () => {
    const btn = document.getElementById("create_cita_admin"); // Obtiene el botón de crear/actualizar cita
    if(btn){
        btn.replaceWith(btn.cloneNode(true))
        const newEdit = document.getElementById("create_cita_admin");
        newEdit.addEventListener("click" , (e) => { // Evento click para crear/actualizar
            e.preventDefault();
            const form  = document.getElementById("form_citas_admin") // Obtiene el formulario
            const formData = new FormData(form) // Crea FormData con los datos del formulario
            formData.append("title" , "citas_admin"); // Agrega el parámetro de acción
            formData.append("idUser" , idUser); // Agrega el id del usuario
            fetch("../presenters/Citas.php", {
                method:"POST",
                body: formData
            }).then(response => response.text())
            .then(data => {
                if(data==="redirect"){
                    //window.location.href = "/views/auth/login.html";
                }else if(data === "success"){
                    alert("creado con exito");
                    closeModalAdmin();
                    form.reset();
                    load_citas_by_user_table(idUser);
                }else if(data ==="Error: La fecha de la cita no puede ser anterior a la fecha actual."){
                    alert(data);
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

// Cierra el modal de administración y resetea el formulario
closeModalAdmin=()=>{
    const close = document.getElementById("close_modal"); // Obtiene el botón de cerrar modal
    if(close){
        close.addEventListener("click" , ()=>{
            const modal = document.getElementById("modal_cita_create"); // Obtiene el modal
            if(modal){
                modal.style.display='none'; // Oculta el modal
                const form = document.getElementById("form_citas_admin"); // Obtiene el formulario
                if(form){
                    form.reset(); // Resetea el formulario
                }
            }
        })
    }
}

// Agrega eventos a la tabla de citas para editar o eliminar como admin
edit_cita_modal=()=>{
    const table = document.getElementById("table_body_citas_by_user"); // Obtiene la tabla de citas
    if(table){
        table.replaceWith(table.cloneNode(true))
        const newTable = document.getElementById("table_body_citas_by_user")
        newTable.addEventListener("click" , (e) => { // Evento click en la tabla
            const btn = e.target.closest("#admin_edit_cita"); // Botón de editar
            if(btn){
                closeModalAdmin(); // Cierra el modal si está abierto
                const cita = JSON.parse(btn.getAttribute("data-item")) // Obtiene los datos de la cita
                const form = document.getElementById("form_citas_admin") // Obtiene el formulario
                if(form){
                    document.getElementById("idCita").value = cita.idCita; // Llena el id de la cita
                    document.getElementById("fecha_cita").value = cita.fecha_cita; // Llena la fecha
                    document.getElementById("motivo_cita").value = cita.motivo_cita; // Llena el motivo
                    const btn = document.getElementById("create_cita_admin");
                    btn.innerHTML = "Actualizar" // Cambia el texto del botón
                    const modal = document.getElementById("modal_cita_create")
                    if(modal){
                        modal.style.display = 'block'; // Muestra el modal
                        create_cita_admin(); // Inicializa evento para actualizar cita
                    }
                }
            }else if(e.target.closest("#admin_delete_cita")){ // Si se hace click en eliminar
                const btn = e.target.closest("#admin_delete_cita");
                if(btn){
                    const idCita = btn.getAttribute("data-id")
                    if(confirm('Esta seguro de eliminar')){
                        const formData = new FormData();
                        formData.append("title" , "delete_cita")
                        formData.append("idCita" , idCita)
                        fetch("../presenters/Citas.php" , {
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

// Función de inicialización al cargar la página
window.initCitas = () => {
    load_citas(); // Carga la tabla de usuarios con citas
    load_citas_user(); // Carga las citas del usuario actual
    saveCita(); // Inicializa el guardado de citas
    open_modal_cita_admin(); // Inicializa el modal de administración
}
 // Ejecuta init cuando el DOM esté listo