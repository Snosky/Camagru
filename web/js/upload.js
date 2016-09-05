var preview = document.getElementById('preview');
var upload = document.getElementById('fileToUpload');
var clear = document.getElementById('reset');

function uploadNext(image)
{
    getBase64Image(image);
}

// On image add
upload.addEventListener('change', function(e){
    e.preventDefault();

    var file = this.files[0];

    if (file && file.type.includes('image/') && !file.type.includes('gif'))
    {
        var oldImage = document.getElementById('main-image');
        if (oldImage)
            preview.removeChild(oldImage);

        var image = new Image(640, 480);
        image.src = URL.createObjectURL(file);
        image.setAttribute('id', 'main-image');
        image.setAttribute('crossOrigin', 'anonymous');
        image.onload = function (){
            uploadNext(image);
        };
        image.className = 'myImage';

        preview.appendChild(image);

        if (preview.getElementsByClassName('border').length >= 1)
            toggleEnable('save');
    }
    else if (file)
    {
        alert('Uploaded file is not valid.');
        upload.value = "";
        disableElem('save');
    }
});


// Clear and Retry button
reset.addEventListener('click', function(){
    upload.value = "";
    preview.innerHTML = "";
    document.getElementById('frame-form').value = '';
    disableElem('save');
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
        }

        preview.appendChild(border);
        document.getElementById('frame-form').value = border.src;

        if (upload.value.length != 0)
            toggleEnable('save');
    });
}