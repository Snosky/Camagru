/* My Functions */
function enableElem(id)
{
    var elem = document.getElementById(id);
    if (!elem)
        console.log('Error enable ID '+ id +' doesn\'t exist.');

    if (elem.className.indexOf('disabled'))
    {
        elem.className = elem.className.replace('disabled', '');
        elem.removeAttribute('disabled');
    }
}

function disableElem(id)
{
    var elem = document.getElementById(id);
    if (!elem)
        console.log('Error disbaled ID '+ id +' doesn\'t exist.');

    if (elem.className.indexOf('disabled') === -1)
    {
        elem.className = elem.className + " disabled";
        elem.setAttribute('disabled', 'disabled');
    }
}

function toggleEnable(id)
{
    var elem = document.getElementById(id);
    if (elem.className.indexOf('disabled'))
        enableElem(id);
    else
        disableElem(id);
}

function addHideClass(id)
{
    var elem = document.getElementById(id);
    if (!elem)
            console.log('Error hide ID '+ id +' doesn\'t exist.');

    elem.className = elem.className + " hidden";
}

function getBase64Image(img) {
    var canvas = document.createElement("canvas");
    var ctx = canvas.getContext("2d");

    canvas.width = img.width;
    canvas.height = img.height;
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    var dataURL = canvas.toDataURL("image/png");

    return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
}

function like(image_id)
{
    var url = "/like/" + image_id;

    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            console.log(xhr.responseText)
        }
    };

    xhr.open('GET', url);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send();
}
/* End My functions */

/* Nav mobile */
(function(){
    var menuButton = document.getElementById('mobile-nav');
    var isOpen = false;
    var menu = document.getElementById('main-nav');

    menuButton.addEventListener('click', function(){
        if (isOpen)
        {
            menu.className = menu.className.replace(' open', '');
        }
        else
        {
            menu.className += " open";
        }
        isOpen = (isOpen) ? false : true;
    });
})();