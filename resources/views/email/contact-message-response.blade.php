{{-- resources/views/emails/donation-confirmation.blade.php --}}
<p>${{ $response }}</p>
<p>&ndash; Drew (SilentMode)</p>

<p>This is in response to your message, sent
    on {{ $original_message->created_at->format(config('contest.date_format')) }}:</p>
<p>{{ $original_message->body }}</p>
