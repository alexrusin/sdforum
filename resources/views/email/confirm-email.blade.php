@component('mail::message')
# One Last Step

We just need to confirm your email address to prove that you are human.  You get it, right?

@component('mail::button', ['url' => route('confirm-email') . '?token=' . $user->confirmation_token])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
