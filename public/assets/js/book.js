var req = new XMLHttpRequest();
req.open('GET', '//photosynthernet.com/boars/add', true);
req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
req.send(JSON.stringify({data : 'thing'}));

req.onreadystatechange = function() {
  var DONE = 4;
  var OK = 200;
  if(req.readyState === DONE) {
    if(req.status === OK) {
      alert(req.responseText);
    }
  }
};
