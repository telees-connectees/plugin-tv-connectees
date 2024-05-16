var meteoRequest = new XMLHttpRequest();
var longitude = 5.4510;
var latitude = 43.5156;
var apiKey = 'dea48adba4f94388a0e155135241402'
var url = "https://api.weatherapi.com/v1/current.json?key=" + apiKey + "&q=" + latitude + "," + longitude;

function refreshWeather() {
    meteoRequest.open('GET', url, true);
    meteoRequest.setRequestHeader('Accept', 'application/json');
    meteoRequest.send();
}

meteoRequest.onload = function () {
    var json = JSON.parse(this.responseText);

    var temp = Math.round(json.current.temp_c);
    var vent = getWind(json).toFixed(0);
    var humidity = json.current.humidity;
    //var sunsetTime = new Date(json.sys.sunset * 1000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    console.log(json.main);
    // Mise à jour de l'affichage
    document.getElementById('temperature').textContent = temp + "°C";
    document.getElementById('wind').textContent = vent + " KM/H - " + humidity + "% humidité";
    document.getElementById('weatherIcon').src = getIcon(json.current.condition.text);
    console.log(json.current.condition.icon)
    //document.getElementById('sunsetTime').textContent = "Le soleil se couche à " + sunsetTime;
};

function getTemp(json) {
    return kelvinToC(json.current.temp_c);
}

function getWind(json) {
    return json.current.wind_kph;
}


function kelvinToC(kelvin) {
    return kelvin - 273.15;
}


function msToKmh(speed) {
    return speed * 3.6;
}

function getIcon(weather){
    switch (weather){
        case 'Sunny':
        case 'Clear':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/01d.svg';
        case 'Partly cloudy':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/02d.svg';
        case 'Cloudy':
        case 'Overcast':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/03d.svg';
        case 'Patchy light snow with thunder':
        case 'Thundery outbreaks possible':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/04d.svg';
        case 'Mist':
        case 'Fog':
        case 'Freezing fog':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/50d.svg';
        case 'Patchy rain possible':
        case 'Patchy light drizzle':
        case 'Patchy light rain':
        case 'Light rain':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/10d.svg';
        case 'Patchy snow possible':
        case 'Patchy sleet possible':
        case 'Patchy freezing drizzle possible':
        case 'Blowing snow':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/13d.svg';
        case 'Moderate rain at times':
        case 'Moderate rain':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/15d.svg';
        case 'Heavy rain at times':
        case 'Heavy rain':
             return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/16d.svg';
        case 'Light drizzle':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/14d.svg';
        case 'Moderate or heavy rain with thunder':
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/17d.svg';
        default:
            return '/wp-content/plugins/ptut-2-tv-connectees/public/img/conditions/03d.svg';

    }
}

refreshWeather();
