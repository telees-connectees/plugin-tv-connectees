let courseList = document.querySelector('.course-config-container').children;
let champRecherche = document.querySelector('#champ-recherche-cours');

champRecherche.addEventListener('input', () => {
    for(let i of courseList){
        let texte = i.querySelector(".course-config p");
        if(texte !== null && texte.innerText.toLowerCase().includes(champRecherche.value.toLowerCase())){
            i.style.display = "block";
        }else{
            i.style.display = "none";
        }
    }
})
