

function load_usuarios(){
    //asignamos el id table_usuarios_admin a una variable
    const table_usuarios = document.getElementById('table_usuarios_admin');
    //validamos si existe el id
    if(table_usuarios){
        //se cargan los datos para buscar los usuarios
        const formData = new FormData();
        formData.append("title" , "get_all_usuarios");
        fetch('../../presenters/Usuarios.php',{
            method:'POST',
            body:formData
        }).then(response => response.text())
        .then(data => {
            document.getElementById("table_contenido_usuarios_admin").innerHTML = data;
            delete_user();
        }).catch(error =>{
            console.log(error)
        })
    }
}

function delete_user(){
    document.querySelector("#table_usuarios_admin").addEventListener("click", (e)=>{
        if(e.target.closest(".btn-rounded-danger")){
            const btn = e.target.closest(".btn-rounded-danger")
            const id = btn.getAttribute("data-id");

            if(confirm("¿Estas seguro de eliminar el registro?")){
                //console.log(id)
                const formData = new FormData();
                formData.append("title" , "delete_user");
                formData.append("idUser" , id);
                fetch("../../presenters/Usuarios.php",{
                    method:"POST",
                    body: formData
                }).then(response =>response.text())
                .then(data => {
                    if(data ==="eliminado"){
                        alert("Eliminado con éxito");
                        const row = btn.closest("tr")
                        row.remove();
                    }else{
                        alert("Error al eliminar")
                    }
                })
                
            }
        }else if(e.target.closest(".btn-rounded-success")){
            const edit = e.target.closest(".btn-rounded-success")
            const data = edit.getAttribute("data-user")
            open_modal_edit(data);
        }
    })
}

function open_modal(){
    const btn = document.getElementById("btn_open_modal_create");
    if(btn){
        btn.addEventListener('click' , (e) => {
            e.preventDefault()
            document.getElementById("createModal").style.display = 'block'
        })

        close_modal("createModal" , "close_create")
    }
}

function open_modal_edit(data){
    const user = JSON.parse(data);
    const modal = document.getElementById("editModal").style.display = 'block';
    document.getElementById("edit_nombre").value = user.nombre;
    document.getElementById("edit_apellidos").value = user.apellidos;
    document.getElementById("edit_email").value = user.email;
    document.getElementById("edit_telefono").value = user.telefono;
    document.getElementById("edit_fecha_nacimiento").value = user.fecha_nacimiento;
    document.getElementsByName("edit_sexo").value = user.edit_sexo;
    document.getElementById("edit_direccion").value = user.direccion;
    document.getElementById("edit_rol").value = user.rol;
    editUser();
    close_modal("editModal" , "close_edit")

}

function editUser(){
    const btn = document.getElementById("form_edit_user");
    if(btn){
        
    }
}

function close_modal(modal , id){
    const btn = document.getElementById(id);
    if(btn){
        btn.addEventListener('click' , (e)=>{
            e.preventDefault();
            document.getElementById(modal).style.display  = 'none';
        })
    }
}
/*function create_user(){
    const btn = document.getElementById("btn_register");
    if(btn){
        btn.addEventListener('click', (e)=>{
            e.preventDefault()
            const form = new FormData(document.getElementById("form_register_user"));
            for (const [key, value] of form.entries()) {
  console.log(`${key}: ${value}`);
}
        })
    }
}*/

function init(){
    load_usuarios();
    open_modal();
    //delete_user();

}

document.addEventListener('DOMContentLoaded' , init);