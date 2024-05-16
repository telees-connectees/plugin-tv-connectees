let modifyButton = document.getElementById("modif-button");
let confirmButton = document.getElementById("confirm-button")
let reservButton = document.getElementById("reserv-room-form");
let cancelButton = document.getElementById("cancel-button")

let modificationInputList = document.querySelectorAll("#room-schedule-side-infos input")
let roomTypeSelection = document.querySelector("#room-type-container select")

modifyButton.onclick = () => {
    enterModication()
}

cancelButton.onclick = () => {
    quitModification();
}

confirmButton.onclick = () => {
    let roomName = document.querySelector("#room-info-name").innerText;
    roomName = roomName.replace(' ','+')
    let pcCount = document.querySelector("#pc-nb-count-container input[type=number]").value
    let pcBrokenCount = document.querySelector("#broken-pc-count-container input[type=number]").value
    let chairCount = document.querySelector("#place-nb-container input").value
    let cableTypes = document.querySelector("#cable-type-container input").value;
    let hasComputerBool;
    let hasComputer = document.querySelector("#has-projector-container input");

    if(hasComputer.value === '✓') hasComputerBool = true;
    else{hasComputerBool = false}
    modifyRoom(roomName, pcCount, pcBrokenCount, hasComputerBool, chairCount, cableTypes, roomTypeSelection.value);
    quitModification();
}

function enterModication(){
    modificationInputList.forEach((elem) => {
        elem.style.pointerEvents = 'auto'
        elem.classList.add("editable")
    })
    roomTypeSelection.style.pointerEvents = 'auto'
    roomTypeSelection.classList.add("editable");
    validationScreen.classList.remove("validation-animation")
    modifyButton.style.display = "none";
    modifyButton.style.opacity = "0";
    reservButton.style.display = "none";
    reservButton.style.opacity = "0";
    confirmButton.style.display = "block";
    confirmButton.style.opacity = "1";
    cancelButton.style.display = "block";
    cancelButton.style.opacity = "1";
}

function quitModification(){
    modificationInputList.forEach((elem) => {
        elem.classList.remove("editable")
        elem.style.pointerEvents = 'none'
    })
    roomTypeSelection.style.pointerEvents = 'none'
    roomTypeSelection.classList.remove("editable");

    modifyButton.style.display = "block";
    modifyButton.style.opacity = "1";
    reservButton.style.display = "flex";
    reservButton.style.opacity = "1";
    confirmButton.style.display = "none";
    confirmButton.style.opacity = "0";
    cancelButton.style.display = "none";
    cancelButton.style.opacity = "0";
}

function modifyRoom(roomName, pcCount, brokenComputer, projector, chairCount, connection, roomType){

    let url = "https://" + window.location.host + "/wp-json/amu-ecran-connectee/v1/room?id=" + roomName;
    let data = {
        pcCount: pcCount,
        projector: projector,
        chairCount: chairCount,
        connection: connection,
        brokenComputer: brokenComputer,
        roomType: roomType
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(function(response) {
            let validationScreen = document.querySelector("#validationScreen")
            validationScreen.classList.add("validation-animation")

            return response.json().then(function (O_json) {
                console.log(O_json)
            })
        })
}
