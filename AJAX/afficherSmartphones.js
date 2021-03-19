
$('#boutton').click(function afficherSmartphone() {

    $.ajax({
        url : "liste_smartphone.php",
        type : 'GET',
        dataType : 'json',
        //async : false,
        success : function (liste_smartphone) {
            var div = document.getElementById('listeTelehones');
            liste_smartphone.forEach(smartphone =>
                div.innerHTML +=
                    '<div class=\'cartouche\'>'+
                    '<div class="cardC">'+
                    '<div class="data">'+
                    smartphone.MODEL_TYPE_DE_SMARTPHONE+
                    '</div>'+
                    '<div class="pict">'+
                    '<img src="./imgCatalogue/'+smartphone.IMG_SMARTPHONE+'" alt="'+smartphone.MODEL_TYPE_DE_SMARTPHONE+'">'+
                    '</div> </div> </div>'
            );

        }
    })
    document.getElementById("boutton").remove();

})

