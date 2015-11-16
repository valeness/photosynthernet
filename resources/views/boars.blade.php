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
          <div class="small-6 columns">
            @if($auth)
            <p>
                Drag/Drop this link to your bookmarks bar and then just click it whenever you want to bookmark a page!
            </p>
            <a class="small button" href="javascript:
            (function(){
                var mark_info = document.createElement('div');
                mark_info.setAttribute('data-id', '{{ $user['bookmark_id'] }}');
                document.body.appendChild(mark_info);
                mark_info.setAttribute('id', 'bookmark_id');
                 var markScript = document.createElement('script');
                 markScript.setAttribute('src', '//photosynthernet.com/assets/js/book.js');
                 document.body.appendChild(markScript);
                })()"> Mark Me! </a>
            @else
                Welcome to B.O.A.R.S!
            @endif
          </div>
      </div>

        @include('layouts.scripts')
  </body>
</HTML>
