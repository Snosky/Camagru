window.addEventListener("DOMContentLoaded", function() {

    var img64 = null;

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

    	var cont = document.getElementById("camImagesList");
    	var images = cont.getElementsByTagName('img');

        for (var i = images.length - 1; i >= 0; i--)
        {
            var img = new Image(images[i].clientWidth, images[i].clientHeight);
            img.setAttribute('crossOrigin', 'anonymous');
            img.src = images[i].currentSrc;
            var left = images[i].style.left;
            var top = images[i].style.top;
            context.drawImage(images[i], left.replace('px', ''), top.replace('px', ''), images[i].clientWidth, images[i].clientHeight);
        }
        img64 = canvas.toDataURL('image/jpeg');
        document.getElementById("save").className = document.getElementById("save").className.replace('disabled', '');
    });

    // Reset the snap
    document.getElementById("retry").addEventListener("click", function(){
        context.clearRect(0, 0, 640, 480);
        document.getElementById("save").className = document.getElementById("save").className + " disabled";
        img64 = null;
    });

    // Save the image
    document.getElementById("save").addEventListener("click", function(){
        if (img64 != null)
        {
            console.log('Save');
        }
    });

    //Change the border
    var cadres = document.getElementById("cadres").getElementsByTagName("img");
    for (var i = 0; i < cadres.length; i++)
    {
        cadres[i].addEventListener("click", function(e){
            var border = this.cloneNode(true);
            border.className = "border";

            var imagesList = document.getElementById("camImagesList");
            var del = imagesList.getElementsByClassName("border");
            for (var k = 0; k < del.length; k++)
            {
                imagesList.removeChild(del[k]);
            }

            document.getElementById("camImagesList").appendChild(border);

        });
        /*cadres[i].addEventListener("click", function(){
            img.className = "border";
            var node = document.getElementById("camImagesList");
            node.appendChild(img[0]);
        });*/
    }

    //Add emote
    var emotes = document.getElementById("emotes").getElementsByTagName("img");
    for (var i = 0; i < emotes.length; i++)
    {
        emotes[i].addEventListener('click', function(e){
            var emote = this.cloneNode(true);
            emote.className = "emote";

           document.getElementById("camImagesList").appendChild(emote);
        });
    }
}, false);