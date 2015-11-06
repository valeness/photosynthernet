<nav class='top-bar' data-topbar role='navigation'>
  <ul class='title-area'>
    <li class='name'>
      <h1><a href='/boars'>B.O.A.R.S.</a></h1>
    </li>
  </ul>

    <section class="top-bar-section">
        <!-- Left Nav -->
        <ul class="left">
            @if(isset($user['id']))
                <li><a href="/boars/bookmarks">Bookmarks</a></li>
            @else
                <li><a href="/boars/login">Login</a></li>
                <li><a href="/boars/register">Register</a></li>
            @endif
        </ul>
    </section>
</nav>