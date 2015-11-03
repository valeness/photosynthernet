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

                <label for="email">Email: </label>
                <input type="text" id="email" placeholder="Email">

                <label for="pass">Password: </label>
                <input type="password" id="pass">

                <label for="confirm_pass">Confirm Password: </label>
                <input type="password" id="confirm_pass">

                <input type="hidden" id="_token" value="{{ csrf_token() }}">

                <button id="register_submit" class="small success">Register</button>
            </div>
        </div>

        @include('layouts.scripts')
    </body>
</HTML>