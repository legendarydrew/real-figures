<h1>Thank you!</h1>
<p>Hello ${{ $donation->name }},</p>
<p>Just a quick email to acknowledge your Golden Buzzer donation of <strong>${{ $donation->currency }}
        ${{ number_format($donation->amount, 2) }}</strong>, for <strong>{{ $donation->song->act->name }}'s
        entry</strong>.</p>
<p>On behalf of "CATAWOL Records", thank you for your support!</p>
<p>&ndash; Drew (SilentMode)</p>
