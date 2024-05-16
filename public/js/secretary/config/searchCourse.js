let champText = document.getElementById("champ-recherche-cours")
let listeCours = document.querySelector(".course-config-container").getElementsByClassName("course-config");

champText.addEventListener('input', () => {
    Array.from(listeCours).forEach((elem) => {
        let texteCourse = elem.getElementsByTagName("p")[0]
        if(texteCourse.innerText.toLowerCase().includes(champText.value.toLowerCase())){
            elem.style.display = "block";
        }else{
            elem.style.display = "none";
        }
        console.log(champText.innerText)
    })
})