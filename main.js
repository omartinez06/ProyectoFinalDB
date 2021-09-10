function generateTime() {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

    let timeNow = new Date();

    let hours = timeNow.getHours().toString().length < 2 ? "0" + timeNow.getHours() : timeNow.getHours();
    let minutes = timeNow.getMinutes().toString().length < 2 ? "0" + timeNow.getMinutes() : timeNow.getMinutes();
    let seconds = timeNow.getSeconds().toString().length < 2 ? "0" + timeNow.getSeconds() : timeNow.getSeconds();

    let mainTime = hours + ":" + minutes + ":" + seconds;
    let currDate = timeNow.toLocaleDateString("es-ES", options);
    document.getElementById("time").innerHTML = mainTime;
    document.getElementById("date").innerHTML = currDate;
}

setInterval(() => {
    generateTime();
}, 1000);