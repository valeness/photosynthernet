var bookmark_id = document.getElementById('bookmark_id').getAttribute('data-id');
var url = window.location.href;
var req = new XMLHttpRequest();
req.open('GET', '//photosynthernet.com/boars/add/' + bookmark_id, true);
req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
req.send(null);

var alert_div = document.createElement('div');
alert_div.setAttribute('style', 'position: fixed; top: 0; background: #F04124; color: #FFF; width:100%; text-align: center; margin: 0 !important;');

req.onreadystatechange = function() {
    var DONE = 4;
    var OK = 200;
    if(req.readyState === DONE) {
        if(req.status === OK) {
            alert_div.innerHTML = req.responseText;
            document.body.setAttribute('style', 'margin: 0');
            document.body.appendChild(alert_div);
            setTimeout(function() {
                alert_div.setAttribute('style', 'display: none;');
                document.body.removeAttribute('style');
            }, 6000);
        }
    }
};