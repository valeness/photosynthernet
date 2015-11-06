var bookmark_id = document.getElementById('bookmark_id').getAttribute('data-id');
var url = window.location.href;
var req = new XMLHttpRequest();
req.open('GET', '//photosynthernet.com/boars/add/' + bookmark_id, true);
req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
req.send(null);

req.onreadystatechange = function() {
    var DONE = 4;
    var OK = 200;
    if(req.readyState === DONE) {
        if(req.status === OK) {
            alert(req.responseText);
        }
    }
};