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
        <div class="small-4 columns">
            <input type="text" id="search" placeholder="Search">
        </div>
      </div>
      <div class="row">
          <div class="small-12 columns">
              <table>
                  <thead>
                      <th>Name</th>
                      <th>Url</th>
                      <th>Actions</th>
                  </thead>
                  @foreach($bookmarks as $bookmark)
                      <tr>
                          <td>{{ $bookmark['name'] }}</td>
                          <td>
                              <a href="{{ $bookmark['url'] }}">{{ $bookmark['url'] }}</a>
                          </td>
                          <td>
                            <span data-id="{{ $bookmark['id'] }}" class="del-bookmark">Delete</span>
                          </td>
                      </tr>
                  @endforeach
              </table>
          </div>
      </div>

      @include('layouts.scripts')
  </body>
</HTML>
