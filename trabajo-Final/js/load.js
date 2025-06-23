document.addEventListener('DOMContentLoaded' , function(){
    fetch('/views/header/header.html')
    .then(response => response.text())
    .then(data => {
        document.getElementById('header-container').innerHTML = data;
    })


    document.querySelector('nav').addEventListener('click', function (e){

        console.log(e)
        if(e.target.tagName ==='A'){
            const allLinks = document.querySelectorAll('nav a');
            allLinks.forEach(link => link.classList.remove('active'));

            e.target.classList.add('active')
        }
    })
})

