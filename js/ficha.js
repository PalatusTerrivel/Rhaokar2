$(document).ready(function() {
    /*BBA*/
    var BBA = parseInt(document.getElementById('BBA').innerText)
    /*modificadores*/
    var modSTR = parseInt(document.getElementById('modSTR').innerText)
    var modDEX = parseInt(document.getElementById('modDEX').innerText)
    var modCON = parseInt(document.getElementById('modCON').innerText)
    var modINT = parseInt(document.getElementById('modINT').innerText)
    var modWIS = parseInt(document.getElementById('modWIS').innerText)
    var modCHA = parseInt(document.getElementById('modCHA').innerText)
    /*melee*/
    var meleeSize = parseInt(document.getElementById('meleeSize').innerText)
    var meleeOther = parseInt(document.getElementById('meleeOther').innerText)
    var Melee = (BBA + modSTR + meleeSize + meleeOther)
    /*Ranged*/
    var modDEX = parseInt(document.getElementById('modDEX').innerText)
    var rangedSize = parseInt(document.getElementById('rangedSize').innerText)
    var rangedOther = parseInt(document.getElementById('rangedOther').innerText)
    var Ranged = (BBA + modDEX + rangedSize + rangedOther)
});