let selector = document.querySelector("#room-choice-form select");
let roomName = document.querySelector("#room-info-name")
let pcNb = document.querySelector("#pc-nb-count-container input")
let brokenPcNb = document.querySelector("#broken-pc-count-container input")
let placeNb = document.querySelector("#place-nb-container input");
let hasVideoProjector = document.querySelector("#has-projector-container input")
let cableList = document.querySelector("#cable-type-container input");
let roomDisponibility = document.querySelector("#room-status-container p")
let hiddenRoomName = document.getElementById("hiddenRoomName")

window.onload = () => {
    loadInfos(selector.value)};

hasVideoProjector.onclick = () => {
    if(hasVideoProjector.value === '✓') hasVideoProjector.value = '❌'
    else{
        hasVideoProjector.value = '✓';
    }
}

function loadInfos(roomName){

    hiddenRoomName.value = roomName;
    roomName = roomName.replace(' ','+')
    let url = "https://" + window.location.host + "/wp-json/amu-ecran-connectee/v1/room?id=" + roomName;
    fetch(url).then(function(response){
        return response.json().then(function(O_json){
            displayInfos(O_json[0]);
        })
    })

    console.log(window.location.host)
}

function displayInfos(O_Json){
    roomName.innerText = O_Json['name']
    pcNb.value = O_Json['pcAvailable']
    placeNb.value = O_Json['placeAvailable']
    cableList.value = O_Json['cablesTypes']
    brokenPcNb.value = O_Json['brokenComputer']

    console.log(O_Json)
    if(O_Json['status'] === 'available'){
        roomDisponibility.className = "room-open"
        roomDisponibility.innerText = "Disponible"
    }else if(O_Json['status'] === 'locked'){
        roomDisponibility.className = "room-locked";
        roomDisponibility.innerText = "Verrouiller"
    }else{
        roomDisponibility.className = "room-occupied"
        roomDisponibility.innerText = "Indisponible"
    }

    if(O_Json['hasVideoProjector']){
        hasVideoProjector.value = '✓'
    }else{
        hasVideoProjector.value = '❌'
    }


}