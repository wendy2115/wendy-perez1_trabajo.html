
perfil = () => {
    const form = document.getElementById("form_perfil");
    if (form) {
        const formData = new FormData();
        formData.append('title', 'perfil');
        fetch('../../presenters/Login.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                if (data === "redirect") {
                    window.location.href = "../../views/auth/login.html";

                } else {
                   form.innerHTML = data;
                   openModal();
                   updated_perfil();
                }
            })
            .catch(error => console.error('Error:', error));
    }
}
updated_perfil = () => {
 const btn = document.getElementById("btn_update_perfil");
 if(btn){
    btn.addEventListener("click" , (e) => {
        e.preventDefault();
        const formData = new FormData(document.getElementById("form_perfil"));
        formData.append("title" , "actualizar_perfil");
        fetch("../../presenters/login.php",{
            method:"POST",
            body:formData
        }).then(response => response.text())
        .then(data => {
            if(data === "success"){
                alert("Perfil actualizado con éxito")
            }else if(data === "redirect"){
                window.location.href = "/views/auth/login.html";
            }else if(data =="No puede haber campos vacios")
        {
            alert("No puede haber campos vacios")
        }
            else{
                alert("No fue posible actualizar");
            }
        })
    })
 }
}
openModal = () => {
    const btn = document.getElementById("open_modal_contrasena");
    if (btn) {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Modal button clicked");
            const modal = document.getElementById("contasenamodal");
            if (modal) {
                modal.style.display = "block";
                closeModal();
            }
        });
    }
}
closeModal = () => {
    const modal = document.getElementById("contasenamodal");
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.id === "close_modal_contrasena") {
                modal.style.display = "none";
            }
        });
    }
}

cambiar_contrasena = () => {
    const btn = document.getElementById("btn_updated");
    if (btn) {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const form = document.getElementById("form_contrasena");
            const formData = new FormData(form);
            formData.append('title', 'update_contrasena');
            fetch('../../presenters/Login.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        alert("Perfil actualizado exitosamente.");
                        window.location.href="/views/auth/login.html";
                    } else if (data === "Actual contraseña no coincide") {
                        alert("Actual contraseña no coincide");
                    } else {
                        alert("Error al actualizar el perfil: " + data);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
}

init = () => {
    perfil();
    cambiar_contrasena();
}

document.addEventListener('DOMContentLoaded', init);