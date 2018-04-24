@component('mail::message')

{{ $content['body'] }}


@component('mail::button', ['url' => route('invite.accept', ['token' => $invite->token])])
{{ $content['button'] }}
@endcomponent


@component('mail::subcopy')
This email and any attachments to it may be confidential and are intended solely for the use of the individual to whom it is addressed.

If you are not the intended recipient of this email, you must neither take any action based upon its contents, nor copy or show it to anyone.

Please contact the sender if you believe you have received this email in error.
@endcomponent
@endcomponent
