import './app.js';
//*********************** page acceuil *********************
let btnContact = document.getElementById('btnContact');
let image = document.getElementById('img_home');
let contact = document.querySelector('.ContactHome');



btnContact.addEventListener('click',function(){

image.style.animation= 'imgOpacity 2s forwards ';
contact.style.display='flex';
btnContact.innerHTML="Retour";

btnContact.addEventListener('click',function(){

    contact.style.animation= 'contactAnim 2s forwards reverse';
    image.style.animation= 'imgOpacity 2s forwards reverse';
    btnContact.addEventListener('click', location.reload(), false);
    
    })
})

