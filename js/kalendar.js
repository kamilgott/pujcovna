/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    var std = $('td.zadano,td.volno');
    var td_zadano = $('td.zadano');
    var td_volno = $('td.volno');

    //std.hide();			//schova citat
//    $('.button').click(function() { //pri zmacknuti tlactka
//        std.show(500);			//ukaze citat
//    });
//    
    std.hover(function() { //mys je na odkaze
        $(this).css('border', '3px solid black');
        //posun se doprava o 10px rychlosti 200ms 
    }, function() { //mys je mimo odkaz
        $(this).css('border', '1px solid black');
        //vrat se zpatky
    });

    std.click(function() {
        $(this).css('border', '5px solid blue');
        var klas = 'volno';
        if ($(this).hasClass('zadano'))
            klas = 'zadano';

        // alert(" text=" + $(this).text() + " klas=" + klas);
        var objekt = $(this).parents(".kalendar").attr("objekt");
        var datum = $(this).parents(".kalendar").attr("rok") + "-" +
                $(this).parents(".kalendar").attr("mesic") + "-" +
                $(this).text();
        //    alert(" datum=" + datum);

        jQuery.ajax({
            url: "/modely/ajax_kalendar.php",
            data: "akce=" + klas + "&datum=" + datum + "&objekt=" + objekt,
            cache: false,
            success: function(html) {
                if (html !== "OK") {
                    alert("Nepodařilo se nastavit kalendař." );
                    location.reload();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Chyba.\n" + textStatus + " " + errorThrown);
            }
        });
        $(this).toggleClass('zadano');
        $(this).toggleClass('volno');

//        alert("po ajax OK=" + OK + "  this.text()=" + $(this).text() +
//                "\n\n\n potom zadano=" + $(this).hasClass('zadano') +
//                " \n\n\n\n\n\n volno=" + $(this).hasClass('volno'));
//        alert("po 2x ajax OK=" + OK );


    });



});

    