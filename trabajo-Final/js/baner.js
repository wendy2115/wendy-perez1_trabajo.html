let imagenMostrada = 1; // Variable para llevar el control de la imagen mostrada
let imagen1 = document.getElementById('banner-img-1').classList; // Obtiene la lista de clases de la imagen 1
let imagen2 = document.getElementById('banner-img-2').classList; // Obtiene la lista de clases de la imagen 2
let imagen3 = document.getElementById('banner-img-3').classList; // Obtiene la lista de clases de la imagen 3

setInterval(() => { // Ejecuta el bloque cada 4 segundos
  imagenMostrada = imagenMostrada === 3 ? 1 : imagenMostrada + 1; // Cambia el número de la imagen mostrada (1->2->3->1)

  if (imagenMostrada === 1) { // Si debe mostrarse la imagen 1
    imagen1.add('banner-img-mostrar'); // Muestra la imagen 1
    imagen2.remove('banner-img-mostrar'); // Oculta la imagen 2
    imagen3.remove('banner-img-mostrar'); // Oculta la imagen 3
  } else if (imagenMostrada === 2) { // Si debe mostrarse la imagen 2
    imagen1.remove('banner-img-mostrar'); // Oculta la imagen 1
    imagen2.add('banner-img-mostrar'); // Muestra la imagen 2
    imagen3.remove('banner-img-mostrar'); // Oculta la imagen 3
  } else if (imagenMostrada === 3) { // Si debe mostrarse la imagen 3
    imagen1.remove('banner-img-mostrar'); // Oculta la imagen 1
    imagen2.remove('banner-img-mostrar'); // Oculta la imagen 2
    imagen3.add('banner-img-mostrar'); // Muestra la imagen 3
  }
}, 4000); // Intervalo de 4 segundos
