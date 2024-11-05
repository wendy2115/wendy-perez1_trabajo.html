let imagenMostrada = 1;
let imagen1 = document.getElementById('banner-img-1').classList;
let imagen2 = document.getElementById('banner-img-2').classList;
let imagen3 = document.getElementById('banner-img-3').classList;

setInterval(() => {
  imagenMostrada = imagenMostrada === 3 ? 1 : imagenMostrada + 1;

  if (imagenMostrada === 1) {
    imagen1.add('banner-img-mostrar');
    imagen2.remove('banner-img-mostrar');
    imagen3.remove('banner-img-mostrar');
  } else if (imagenMostrada === 2) {
    imagen1.remove('banner-img-mostrar');
    imagen2.add('banner-img-mostrar');
    imagen3.remove('banner-img-mostrar');
  } else if (imagenMostrada === 3) {
    imagen1.remove('banner-img-mostrar');
    imagen2.remove('banner-img-mostrar');
    imagen3.add('banner-img-mostrar');
  }
}, 4000);
