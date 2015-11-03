<HTML>
  <head>
      @include('layouts.head')
  </head>

  <body>
    @include('layouts.nav')

      <div class="row">
          <div class="small-12 columns text-center">
              <h1>B.O.A.R.S. | Bookmarks on a Remote Server</h1>
          </div>
      </div>
      <div class="row">
          <div class="small-6 columsn">

          </div>
          <div class="small-6 columsn">
            @if($user['id'] != '')
            <p>
                Drag/Drop this link to your bookmarks bar and then just click it whenever you want to bookmark a page!
            </p>
            <a class="small button" href="javascript:
            (function(){
                var bookmark_id = '{{ $user['bookmark_id'] }}';
                var url = window.location.href;
                var req = new XMLHttpRequest();
                req.open('GET', '//photosynthernet.com/boars/add?url=' + url + '&bookmark_id=' + bookmark_id, true);
                req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                req.setRequestHeader('Access-Control-Allow-Origin', '*');
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

                })()"> Mark Me! </a>
            @endif
          </div>
      </div>

        @include('layouts.scripts')
  </body>
</HTML>
