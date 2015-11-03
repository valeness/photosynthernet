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
              <p>
                  Drag/Drop this link to your bookmarks bar and then just click it whenever you want to bookmark a page!
              </p>
            <a class="small button" href="javascript: (function () {
                var markScript = document.createElement('script');
                markScript.setAttribute('src', '//photosynthernet.com/assets/js/book.js');
              document.body.appendChild(markScript);
            }());"> Mark Me! </a>
          </div>
      </div>

        @include('layouts.scripts')
  </body>
</HTML>
