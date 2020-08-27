window.onload = function setFontSize() {
    var length = document.getElementsByClassName('beer_name').length
    for (let i=0; i < length; i++) {
        var beer = document.getElementsByClassName('beer_name')[i]
        var beername = beer.textContent;

        if (beername.length <= 22) {
            console.log(beer)
            beer.style.fontSize = '13px';
        }
    }
}