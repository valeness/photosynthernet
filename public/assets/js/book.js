var req = new XMLHttpRequest();
req.open('GET', '//photosynthernet.com/marky/add', true);
req.send(null);

req.onreadystatechange = function() {
  var DONE = 4;
  var OK = 200;
  if(req.readyState === DONE) {
    if(req.status === OK) {
      alert(req.responseText);
    }
  }
}
