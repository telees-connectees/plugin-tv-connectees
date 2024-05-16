function deleteRoom(elem){
    name = elem.parentElement.querySelector("input[type=hidden]").value;

    let url = "https://" + window.location.host + "/wp-json/amu-ecran-connectee/v1/room";
    let data = {
        name: name,
    };

    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(function(response) {
        elem.parentElement.remove();
        return response.json().then(function (O_json) {
            console.log(O_json)
        })
    })
}