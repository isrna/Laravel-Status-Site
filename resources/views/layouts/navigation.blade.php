<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <a class="navbar-brand" href="{{ config('app.url') }}" title="{{ config('app.name', 'Service Status') }}">
        <img src="/images/brand.png" width="38" height="38" class="d-inline-block align-top" alt="{{ config('app.name', 'Service Status') }}">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item">
                <a class="nav-link" href="https://isrna.com/" target="_blank" title="Home">Home</a>
            </li>
        </ul>
        <span class="navbar-text">
			  Service status - Refreshing in <span id="refresh_time"></span>
			</span>
    </div>
</nav>
