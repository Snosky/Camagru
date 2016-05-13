window.addEventListener("DOMContentLoaded", function() {
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

	document.getElementById("snap").addEventListener("click", function() {
    	context.drawImage(video, 0, 0, 640, 480);

    	var cont = document.getElementById("camImagesList");
    	var images = cont.getElementsByTagName('img');

        console.log(images);

        for (var i = 0; i < images.length; i++)
        {
            var img = new Image(images[i].clientWidth, images[i].clientHeight);
            img.src = images[i].currentSrc;
            console.log(img);
            //img.width =
            context.drawImage(images[i], 0, 0, images[i].clientWidth, images[i].clientHeight);
        }
    });
}, false);