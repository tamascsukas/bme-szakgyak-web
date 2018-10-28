Az új fiók aktiválásához kattints a linkre:<br><br>

{{ $token->user->name }}<br>
{{ $token->user->email }}<br><br>

<a href="{{ route('auth.verify', $token) }}">Aktiáválás ></a>