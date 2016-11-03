<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">TrackerBot</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @if(!Auth::guest())
                    <li class="{{ (Request::is('/dashboard') ? 'active' : '') }}">
                        <a href="/dashboard">Dashboard</a>
                    </li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li class="{{ (Request::is('login') ? 'active' : '') }}"><a href="/login"> Login</a></li>
                    <li class="{{ (Request::is('register') ? 'active' : '') }}"><a
                                href="/register">Register</a></li>
                @else
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"> {{ Auth::user()->name }} <i
                                    class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/logout"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
