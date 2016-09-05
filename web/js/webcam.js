var is_valid = 0;

window.addEventListener("DOMContentLoaded", function() {

    var img64 = null;
    var useVideo = true;

	// Grab elements, create settings, etc.
	var canvas = document.getElementById("canvas"),
		context = canvas.getContext("2d"),
		video = document.getElementById("videoElement"),
		videoObj = { "video": true },
		errBack = function(error) {
			console.log("Video capture error: ", error.code);
		};

	// Put video listeners into place
	if(navigator.getUserMedia) { // Standard
		navigator.getUserMedia(videoObj, function(stream) {
			video.src = stream;
			video.play();
		}, errBack);
	} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
		navigator.webkitGetUserMedia(videoObj, function(stream){
			video.src = window.URL.createObjectURL(stream);
			video.play();
		}, errBack);
	}
	else if(navigator.mozGetUserMedia) { // Firefox-prefixed
		navigator.mozGetUserMedia(videoObj, function(stream){
			video.src = window.URL.createObjectURL(stream);
			video.play();
		}, errBack);
	}

    // Take a snap
	document.getElementById("snap").addEventListener("click", function() {
        context.drawImage(video, 0, 0, 640, 480);

    	img64webcam = canvas.toDataURL('image/jpeg');

        //Put on the form
        document.getElementById('formWebcamImage').value = img64webcam;

        is_valid++;

        if (is_valid == 2)
            enableElem('save');
    });

    // Reset the snap
    document.getElementById("reset").addEventListener("click", function(){
        context.clearRect(0, 0, 640, 480);
        img64 = null;
        is_valid = 0;
        disableElem('save');
        document.getElementById('preview').removeChild(document.getElementsByClassName('border')[0]);
    });


    // Frame selection
    var cadres = document.getElementById("layer-list").getElementsByTagName("img");
    for (var i = 0; i < cadres.length; i++)
    {
        cadres[i].addEventListener("click", function(e){
            var border = this.cloneNode(true);
            border.className = "border";

            var imagesList = document.getElementById("preview");
            var del = imagesList.getElementsByClassName("border");
            for (var k = 0; k < del.length; k++)
            {
                imagesList.removeChild(del[k]);
                is_valid--;
            }

            preview.appendChild(border);
            is_valid++;
            document.getElementById('frame-form').value = border.src;

            if (is_valid == 2)
                enableElem('save');
        });
    }
});