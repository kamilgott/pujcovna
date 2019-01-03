/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var texty = [];
texty['Úvod'] = '<b>Aplikace půjčovna</b></br> je půjčovní portál nábytku. </br>' +
        '<b>Zájemce o půjčení</b> </br> najde rychle nejvhodnější ubytování přesně podle svých požadavků a jedná přímo s majitelem.</br>' +
        '<b>Majitel nábytku</b> </br> '
        ;
texty['Ceny'] = '<b>Co Vám můžeme nabídnout: </b></br>' +
        '*  žádné přemrštěné provize, platíte pouze nízký roční poplatek za registraci do katalogu.'
        ;
texty['Jak objednat'] = ' Hledáte-li nábytek vyberte si pomocí <b>výběrových kritérií</b> objekt podle ' +
        ' Vašich představ a zkontrolujte zda v <b>Kalendáři obsazenosti</b> objektu je volný termín pro Vaši objednávku, po té zašlete dotaz ' +
        ' majiteli objektu a tento se pak s Vámi přímo zkontaktuje ......'
        ;
texty['Pro majitele'] = 'Za registrace provedené do konce letošního roku se <b>neplatí nic až do 1.5.2014 </b>' +
        '</br>Kdykoliv se můžete sami rozhodnout, zda se vám naše služby vyplatí a registraci smazat,' +
          '</br></br><b>Vyzkoušejte a získejte zákazníky</b>'
        ,
        texty['O nás'] = 'Jsme nejlepší  půjčovní portál a prostřednictvím našich stránek umožňujeme '  
        ;
texty['Dotaz'] = '</br><b>Každý Váš dotaz</b> nám pomáhá zlepšil služby našeho serveru' +
        '</br> a tím i zajistit <b>Vaši plnou spokojenost</b>';

var OFiltr = new Array(6);
OFiltr ['kategorie'] = new Array(5);
for (var i = 1; i < OFiltr['kategorie'].length; i++)
    OFiltr['kategorie'][i] = false;
OFiltr ['tema'] = new Array(9);
for (var i = 1; i < OFiltr['tema'].length; i++)
    OFiltr['tema'][i] = false;
OFiltr ['sOblast'] = new Array();
OFiltr ['sHory'] = new Array();
OFiltr ['sKraj'] = new Array();
OFiltr['sOkres'] = new Array();


function newNabidka() {
    var objekty = $('#ramVpravo .dlazdice');
    var list="";
    objekty.each(function () {
        var objekt_id = $(this).attr('id');
        objekt_id = objekt_id.substring(4, objekt_id.length);  
        if (list.length == 0) {
            list += objekt_id ;
        } else {
            list += "," + objekt_id;
        }        
    });   
    window.location.href = "/nabidka/new/" + list;
}


function drag(ev) {
      ev.dataTransfer.setData("id", ev.currentTarget.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("id");
    var nodeCopy = document.getElementById(data).cloneNode(true);
    nodeCopy.id = "Zak_"+data;
    ev.target.appendChild(nodeCopy); 
    $('#ramVpravo .dlazdice').on('contextmenu', function (evt) {
        evt.preventDefault();
    });

    $('#ramVpravo .dlazdice').mouseup(function (evt) {
        if (evt.which === 3) { // right-click
            if (evt.originalEvent.detail === 2) {
                          ev.target.removeChild(nodeCopy);
            }
        }
    });
}

$(document).ready(function () {
    $("#menu").menu({ position: { my: "left top-10", at: "right" } });
    $(".tooltip").tooltip();
    $(".dlazdice .info").easyTooltip();

    var obrazky = $('#ram .obrazek, #form-objekt .obrazek');
    obrazky.each(function() {
        var objekt_id = $(this).parent(".dlazdice").attr('id');
        //console.log('objekt_id=' + objekt_id);

//--KG       //jQuery.ajax({
        //    type: "POST",
        //    url: "/modely/ajax_download.php",
        //    data: "objekt_id=" + objekt_id,
        //    cache: true,
        //    success: function(img_data) {
        //        //  console.log('img_data=' + img_data.length);
        //        if (img_data.length > 0)
        //            $("#" + objekt_id + " img").attr({src: img_data})
        //        else
        //            $("#" + objekt_id + " img").attr({src: "/img/icons/nofoto.jpg"});
        //    },
        //    error: function(XMLHttpRequest, textStatus, errorThrown) {
        //        $("#" + objekt_id + " img").attr({src: "/img/icons/nofoto.jpg"});
        //        alert("Chyba.\n" + textStatus + " " + errorThrown);
        //    }
        //});

    });


    $('#hmenu .info').click(function() {
//  alert ('text= '+$(this).text());
        $('#informace p').html(texty[$(this).text()]);
        $('#informace').slideDown();
    });
    $('#informace a').click(function() {
        $('#informace').hide();
    });
    $('#SubMajitel').click(function() {
        if ($('#heslo1').val() !== $('#heslo2').val()) {
            alert('Hesla nejsou shodná!');
        }
    });
    $(".thumbs img").click(function() {
        var largeAlt = $(this).attr("title");
        //    alert('largePath=' + SRC + ' ' + largeAlt)
        $("#largeImg").attr({src: $(this).attr("src"), alt: $(this).attr("title")});
        $("h2 em").html(" (" + largeAlt + ")");
        return false;
    });

    $(".thumbs img").first().click();


    $('#ZapomenuteHeslo').click(function() {
        $('#heslo').attr('disabled', 'disabled').removeAttr('required');
        $('#ZapomenuteDiv').show();
        $('#ZapomenuteDiv input').attr('required', 'required');
        $('#SubForm').hide();

    });


    

});

$(document).ready(function() {
    $("#sOblast").multiselect({
        checkAllText: "vyber vše",
        uncheckAllText: "zruš výběr",
        noneSelectedText: "Výběr oblasti",
        potvrdText: "Vyber",
        minWidth: 130,
        height: 350,
        selectedText: function(numChecked, numTotal, checkedItems) {
            return numChecked + ' oblasti';
        },
        position: {my: 'left bottom', at: 'left top'}
    });
    $("#sKraj").multiselect({
        checkAllText: "vyber vše",
        uncheckAllText: "zruš výběr",
        noneSelectedText: "Výběr kraje",
        potvrdText: "Vyber",
        minWidth: 130,
        height: 350,
        selectedText: function(numChecked, numTotal, checkedItems) {
            return numChecked + ' kraje:';
        },
        position: {my: 'left bottom', at: 'left top'}

    });
    $("#sHory").multiselect({
        checkAllText: "vyber vše",
        uncheckAllText: "zruš výběr",
        noneSelectedText: "Výběr hory",
        potvrdText: "Vyber",
        minWidth: 130,
        height: 350,
        selectedText: function(numChecked, numTotal, checkedItems) {
            return numChecked + ' hor';
        },
        position: {my: 'left bottom', at: 'left top'}
    });
    $("#sOkres").multiselect({
        checkAllText: "vyber vše",
        uncheckAllText: "zruš výběr",
        noneSelectedText: "Výběr okres",
        potvrdText: "Vyber",
        minWidth: 130,
        height: 350,
        selectedText: function(numChecked, numTotal, checkedItems) {
            return numChecked + ' okresy';
        },
        position: {my: 'left bottom', at: 'left top'}
    });

});



$(document).ready(function() {

    var Fvyb = function() {
        // alert(' Vyber jQuery(this).val()=' + jQuery(this).val());
        //  alert("Vyberdata ")
        // alert(' $(this).attr("id")=' + $(this).attr("id"));

        //zjistim zda je vubec neco vybrano
        var gkategorie, gtema, goblast, ghory, gkraj, gokres = false;
        var pocet = 0;

        for (var j in OFiltr['kategorie'])
            gkategorie = gkategorie || OFiltr['kategorie'][j];
        for (var j in OFiltr['tema'])
            gtema = gtema || OFiltr['tema'][j];

        var gVse = OFiltr['sOblast'].length === 0 &&
                OFiltr['sHory'].length === 0 &&
                OFiltr['sKraj'].length === 0 &&
                OFiltr['sOkres'].length === 0;

        //nastavim drazdice
        var dlazdice = $('#ram .dlazdice,#form-objekt .tr_seznam');
        dlazdice.each(function() {
            goblast = false;
            ghory = false;
            gkraj = false;
            gokres = false;

            // console.log(' goblast=' + goblast + ' ghory=' + ghory + ' gkraj=' + gkraj + ' gokres=' + gokres);


            var kshow = (!gkategorie);
            var tshow = true;//(!gtema);
            //  console.log('$(this).attr(kategorie)=' + $(this).attr("kategorie"));
            if (OFiltr['kategorie'][$(this).attr("kategorie")])
                kshow = true;

            if (gtema) //je vybrano nejake tema
                for (var i = 1; i <= OFiltr['tema'].length; i++) {
                    if (OFiltr['tema'][i]) {
                        //     console.log('$(this).attr(tema)' + $(this).attr("tema")[i - 1]);
                        tshow = tshow && ($(this).attr("tema")[i - 1] == 1);
                    }
                }

            for (var i = 0; i < OFiltr['sOblast'].length; i++) {
                if (OFiltr['sOblast'][i] == $(this).attr("oblast")) {
                    //        console.log('Vybrano ' + OFiltr['sOblasti'][i] + ' == ' + $(this).attr("oblast"));
                    goblast = true;
                    break;
                }
            }
            for (var i = 0; i < OFiltr['sHory'].length; i++) {
                if (OFiltr['sHory'][i] == $(this).attr("hory")) {
                    ghory = true;
                    break;
                }
            }
            for (var i = 0; i < OFiltr['sKraj'].length; i++) {
                if (OFiltr['sKraj'][i] == $(this).attr("kraj")) {
                    gkraj = true;
                    break;
                }
            }
            for (var i = 0; i < OFiltr['sOkres'].length; i++) {
                if (OFiltr['sOkres'][i] == $(this).attr("okres")) {
                    gokres = true;
                    break;
                }
            }


            if (kshow && tshow && (goblast || ghory || gkraj || gokres || gVse)) {
                $(this).show();
                pocet++;
            }
            else
                $(this).hide();



        });
        $('#divzpravy').html("<p class='zprava'>Vybráno " + pocet + " objektů</p>");
    }




    var OblastF = $('#divOblasti select');
    // navazu multiclose na Vyber
    OblastF.multiselect({
        close: function() {
            OFiltr[$(this).attr("id")] = $(this).multiselect("getChecked").map(
                    function() {
                        return this.value;
                    }).get();

            //   console.log('XXXXX $(this).attr(id)=' + $(this).attr("id") + ' OFiltr[$(this).attr(id)]=' + OFiltr[$(this).attr("id")]);
            Fvyb();
        }


    });
    //OblastF.multiselect().bind("multiselectclose", Fvyb);
    var std = $('#h_tema div, #h_kategorie div ');
    std.hover(function() { //mys je na odkaze
        $(this).find('label').toggleClass('najeto');
        $(this).find('img').toggleClass('najeto');
    }, function() { //mys je mimo odkaz
        $(this).find('label').toggleClass('najeto');
        $(this).find('img').toggleClass('najeto');
        //vrat se zpatky
    });

    std.click(function() {
        if ($('#akce').val() !== "seznam")
            location = '/objekt/seznam/T';
        //jen pripravim data ve Filtru OFiltr pro funkci Fvyb=Vyber() 
        var nazev = $(this).attr("id").substring(0, $(this).attr("id").length - 1);
        var poradi = $(this).attr("id").substring($(this).attr("id").length - 1, $(this).attr("id").length);
        OFiltr[nazev][poradi] = !OFiltr[nazev][poradi];
      //  console.log('[' + nazev + '][' + poradi + '] = ' + OFiltr[nazev][poradi]);
        //prebarvim vyberove prvky
        $(this).find('img').toggleClass('vybrano');
        $(this).find('label').toggleClass('vybrano');
       /* if (nazev == "kategorie") {
            switch (poradi) {
                case "1":
                    $('title').text('Bezva kempy a tábořiště u vody v lese i na horách');
                    $('footer h1').text('Rychle a přehledně najdete kemp u jezera, přehrady, rybníku, v lese i na horách, Pronajměte si na dovolenou stan, chatku nebo karavan v kempu, kde rostou houby a berou ryby. Vodácký kemp u řeky i tábořiště na sportovní akci');
                     break;
                case "2":
                    $('title').text('Bezva chaty a chatky k pronájmu na dovolenou i na víkend');
                    $('footer h1').text('Snadný,rychlý a přehledný výběr, levné i luxusní chaty v Čechách i na Moravě, najdete srub u vody i horskou chatu. hájovnu i mlýn k pronájmu, vhodný na dovolenou s dětmi a psem. Chaty pro rybáře i turisty.');
                    break;
                case "3":
                    $('title').text('Bezva Chalupy k pronájmu na horách i u vody');
                    $('footer h1').text('Bezvadný a rychlý výběr chalup k pronajmu na dovolenou i víkend. Chalupy levné, i luxusní s vlastním bazénem a tenisovým kurtem, chaloupky u vody, i ubytování v chalupě z bývalého mlýna s tou správnou atmosférou.');
                    break;
                case "4":
                    $('title').text('Priváty, apartmány a ubytování v soukromí a k pronájmu');
                    $('footer h1').text('Velký výběr a srozumitelné třídění ubytování v soukromí, pronájmy horských apartmánů a apartmánových domů. Rekreační priváty a apartmány v lázních, ubytování u vína i pro milovníky kultury, či privát pro milence.');
                     break;
                case "5":
                    $('title').text('Klidné hotely a útulné penziony na horách, u vody i ve městě');
                    $('footer h1').text('Přehledný a rychlý výběr hotelů a penzionů. Vyberte si hotel s vlastním pivovarem, u lyžařské sjezdovky, penzion na Moravě s degustací vína, nebo hotel a penzion ve velkém městě jako Praha nebo Brno.')
                     break;

            }
        }
        */
        Fvyb();
    });

    //pozor na poradi bindu nejdrive click a pak bind !!!
    // navazu clik na Vyber
    // std.bind("click", Vyber);


});



    