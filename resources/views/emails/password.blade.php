@component('mail::message')
# Mot de passe

Bonjour, voici votre mot de passe : {{$password}}

{{ config('app.name') }}
@endcomponent
