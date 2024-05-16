function toggleRoom(element){
    if(element.classList.contains('available')){
        element.classList.remove('available');
        element.classList.add('not-available');
    } else {
        element.classList.remove('not-available');
        element.classList.add('available');
    }
}