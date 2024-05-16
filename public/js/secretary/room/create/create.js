function createRoom(){
    let name = document.querySelector("#roomCreationInfos input[type=text]")
    let type = document.querySelector("#roomCreationInfos select")

    let url = "https://" + window.location.host + "/wp-json/amu-ecran-connectee/v1/room/create";
    let data = {
        name: name.value,
        type: type.value
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(function(response) {
        return response.json().then(function (O_json) {
            console.log(O_json)
        })
    })

    location.reload();




}