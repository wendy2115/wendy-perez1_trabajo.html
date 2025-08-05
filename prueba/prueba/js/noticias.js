// Función para cargar la tabla de noticias
table_noticias = () => {
    const table = document.getElementById("table_noticias") // Obtiene el elemento de la tabla
    if (table) { // Verifica si existe la tabla
        const formData = new FormData(); // Crea un objeto FormData
        formData.append("title", "noticias_all"); // Agrega el parámetro para solicitar todas las noticias
        fetch("../presenters/Noticias.php", { // Realiza una petición fetch al backend
            method: 'POST', // Usa el método POST
            body: formData // Envía el FormData como cuerpo de la petición
        }).then(response => response.text()) // Convierte la respuesta a texto
            .then(data => { // Maneja la respuesta
                if (data === "admin") { // Si la respuesta es "admin"
                    alert("Requiere admin") // Muestra alerta de requerir permisos de admin
                } else { // Si no es "admin"
                    const tableContent = document.getElementById("table_noticias_content") // Obtiene el contenedor de la tabla
                    if (tableContent) { // Verifica si existe el contenedor
                        tableContent.innerHTML = data // Inserta el contenido recibido en la tabla
                        delete_item(); // Inicializa la función para eliminar noticias
                        edit_noticia(); //inicializa la funcion para editar noticias
                    }
                }
            })
    }
}

// Función para editar noticia (vacía, por implementar)
edit_noticia = () => {
    const btn = document.getElementById("edit_noticia") //obtiene el boton de editar
    if(btn) { //verifica si existe
        btn.addEventListener("click" , () => { //agrega evento de click al boton
            close_modal_noticias();
            const data = JSON.parse(btn.getAttribute("data-noti")) // obtiene los datos almacenados en data-noti
            const modal = document.getElementById("modal_noticia")//obtiene el modal
            if(modal){ //verifica si existe
                modal.style.display = 'block'; // hace visible el modal
                document.getElementById("idNoticia").value = data.idNoticia; //asigna idNoticia al input
                document.getElementById("titulo").value = data.titulo; //asigna titulo al input
                document.getElementById("fecha").value = data.fecha; //asigna fecha al input fecha
                document.getElementById("texto").value = data.texto; //asigna texto al input texto
                // Asigna la imagen a la etiqueta img
                const img = document.getElementById("img"); // obtiene la etiqueta img
                if (img && data.imagen) { // verifica si existe la etiqueta y la propiedad imagen
                    img.src = "..//assets/media/"+data.imagen; // asigna la ruta de la imagen
                    img.style.display = "block"; // muestra la imagen si estaba oculta

                }
                const btn_edit = document.getElementById("btn_noticia_editar")//obtiene el boton
                if(btn_edit){//verifica que exista
                    btn_edit.style.display = 'block'; //muestra el boton
                    btn_edit.addEventListener("click" , (e) => { //asigna evento de click
                        e.preventDefault(); //previene el comportamiento por defecto
                        const form = document.getElementById("form_noticia") //asigna campos del formulario a la variable form
                        const formData = new FormData(form); // se asigna los campos al formData
                        formData.append("title" , "update_noticias")
                        fetch("../presenters/Noticias.php" , { //Realiza peticion al backend
                            method:"POST", //
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            if(data === "ok"){
                                alert("Actualizado");
                                table_noticias()
                                form.reset();
                                modal.style.display = 'none';
                            }else if(data === 'admin'){
                                alert("Requiere admin")
                            }
                        })
                    })
                }
            }
        })
    }
}

// Función para eliminar una noticia
delete_item = () => {
    const btn = document.getElementById("delete_noticia"); // Obtiene el botón de eliminar
    if (btn) { // Verifica si existe el botón
        btn.addEventListener("click", () => { // Agrega evento click al botón
            const id = btn.getAttribute("data-id"); // Obtiene el id de la noticia a eliminar
            const formData = new FormData(); // Crea un objeto FormData
            formData.append("title", "delete"); // Agrega el parámetro para eliminar
            formData.append("idNoticia", id); // Agrega el id de la noticia
            if (confirm("Esta seguro de eliminar la noticia")) { // Confirma la eliminación
                fetch("../presenters/Noticias.php", { // Realiza petición fetch al backend
                    method: 'POST', // Usa el método POST
                    body: formData // Envía el FormData como cuerpo de la petición
                }).then(response => response.text()) // Convierte la respuesta a texto
                    .then(data => { // Maneja la respuesta
                        if (data === "admin") { // Si requiere admin
                            alert("Requiere admin");

                        } else if (data === "ok") { // Si fue exitoso
                            alert("Eliminado con éxito")
                            table_noticias() // Recarga la tabla de noticias
                        }
                    })
            }
        })
    }
}

// Función para abrir el modal de creación de noticias
open_modal_noticias = () => {
    const btn = document.getElementById("create_noticia"); // Obtiene el botón de crear noticia
    if (btn) { // Verifica si existe el botón
        btn.addEventListener("click", (e) => { // Agrega evento click al botón
            e.preventDefault(); // Previene el comportamiento por defecto

            const modal = document.getElementById("modal_noticia") // Obtiene el modal
            if (modal) { // Verifica si existe el modal
                modal.style.display = "block"; // Muestra el modal
                close_modal_noticias(); // Inicializa función para cerrar el modal
                event_boton_save(); // Inicializa función para guardar noticia
            }
        })
    }
}

// Función para manejar el botón de guardar noticia
event_boton_save = () => {
    const btn = document.getElementById("btn_noticia_crear") // Obtiene el botón de guardar
    if (btn) { // Verifica si existe el botón
        btn.style.display = 'block';//mostrar si esta oculto
        btn.addEventListener("click", (e) => { // Agrega evento click al botón
            e.preventDefault(); // Previene el comportamiento por defecto
            const form = document.getElementById("form_noticia") // Obtiene el formulario
            const formData = new FormData(form) // Crea FormData con los datos del formulario
            formData.append("title", "create_noticia"); // Agrega el parámetro para crear noticia
            fetch("../presenters/Noticias.php", { // Realiza petición fetch al backend
                method: 'POST', // Usa el método POST
                body: formData // Envía el FormData como cuerpo de la petición
            })
                .then(response => response.text()) // Convierte la respuesta a texto
                .then(data => { // Maneja la respuesta
                    if (data === "admin") { // Si requiere admin
                        alert("Requiere admin");
                    } else if (data === "ok") { // Si fue exitoso
                        alert("Creado con exito");
                        form.reset() // Resetea el formulario
                        btn.hidden = true;
                        document.getElementById("modal_noticia").style.display = "none"; // Oculta el modal
                        table_noticias(); // Recarga la tabla de noticias
                    } else { // Si hay error
                        alert(data);
                    }

                })
        })
    }
}

// Función para cerrar el modal de noticias
close_modal_noticias = () => {
    const btn = document.getElementById("close_modal_noticias"); // Obtiene el botón de cerrar modal
    if (btn) { // Verifica si existe el botón
        btn.addEventListener("click", () => { // Agrega evento click al botón
            const modal = document.getElementById("modal_noticia") // Obtiene el modal
            modal.style.display = "none"; // Oculta el modal
            document.getElementById("form_noticia").reset(); // Resetea el formulario
        })
    }
}

// Función de inicialización al cargar la página
window.initNoticias = () => {
    table_noticias(); // Carga la tabla de noticias
    open_modal_noticias(); // Inicializa el modal de noticias
}

// Ejecuta la función init cuando el DOM esté cargado
