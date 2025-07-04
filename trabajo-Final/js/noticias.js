
table_noticias = () =>{
    const table = document.getElementById("table_noticias")
    if(table){
        const formData = new FormData();
        formData.append("title" , "noticias_all");
        fetch("../../presenters/Noticias.php" , {
            method: 'POST',
            body: formData
        }).then(response => response.text())
        .then(data => {
            if(data === "admin"){
                alert("Requiere admin")
            }else{
                const tableContent = document.getElementById("table_noticias_content")
                if(tableContent){
                    tableContent.innerHTML = data
                }
            }
        })
    }
}

init = () =>{
table_noticias();
}

document.addEventListener("DOMContentLoaded" , init);