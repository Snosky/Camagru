/* Disabled */
var disabled = document.getElementsByClassName('disabled');

for (var i = 0; i < disabled.length; i++)
{
    disabled[i].addEventListener('click', function(e){
        e.preventDefault();
    });
}