

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
                console.log(id)
                const row = btn.closest("tr")
                row.remove();
            }
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
    }
}
function close_modal(){
    const btn = document.getElementById("close_create");
    if(btn){
        btn.addEventListener('click' , (e)=>{
            e.preventDefault();
            document.getElementById("createModal").style.display  = 'none';
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
    close_modal();
    //delete_user();

}

document.addEventListener('DOMContentLoaded' , init);