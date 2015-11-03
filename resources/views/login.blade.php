<HTML>
    <head>
        @include('layouts.head')
    </head>

    <body>
        @include('layouts.nav')

        <div class="row">
            <div class="small-4 columns">
                <label for="username">Username: </label>
                <input type="text" id="username" placeholder="Username">

                <label for="pass">Password: </label>
                <input type="password" id="pass">

                <input type="hidden" id="_token" value="{{ csrf_token() }}">

                <button id="login_submit" class="small success">Login</button>
            </div>
        </div>

        @include('layouts.scripts')
    </body>
</HTML>