/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function resizeAndUpload(file,objekt,poradi) {

    if (!window.FileReader) {
        alert('File APIs není podporováno Vaším prohlížečem! Zaktualizujte si poslední verzi.');
    }

    var reader = new FileReader();
    reader.onloadend = function() {

        var tempImg = new Image();
        tempImg.src = reader.result;
        tempImg.onload = function() {

            var MAX_WIDTH = 450;
            var MAX_HEIGHT = 337;
            var tempW = tempImg.width;
            var tempH = tempImg.height;
            if (tempW > tempH) {
                if (tempW > MAX_WIDTH) {
                    tempH *= MAX_WIDTH / tempW;
                    tempW = MAX_WIDTH;
                }
            } else {
                if (tempH > MAX_HEIGHT) {
                    tempW *= MAX_HEIGHT / tempH;
                    tempH = MAX_HEIGHT;
                }
            }

            var canvas = document.createElement('canvas');
            canvas.width = tempW;
            canvas.height = tempH;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(this, 0, 0, tempW, tempH);
            var dataURL =  canvas.toDataURL("image/jpeg",0.8);
          //  alert('delka='+ dataURL.length);
            var test  = document.getElementById('uploadPreview'); 
            test.src = dataURL;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(ev) {
                document.getElementById('filesInfo').innerHTML = 'Připraveno!';
            };
           xhr.onloadend = function(ev) {
                document.getElementById('filesInfo').innerHTML ='uloženo OK' ;
            };

            xhr.open('POST', '/modely/ajax_upload.php', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var data = 'image=' + dataURL + "&velikost=" + file.size + "&nazev=" + file.name;
              data += "&typ=" + file.type+"&poradi=" + poradi + "&objekt="+objekt;
            xhr.send(data);
        }

    }
    reader.readAsDataURL(file);
}
