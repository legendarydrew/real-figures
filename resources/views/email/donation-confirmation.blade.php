{{-- resources/views/emails/donation-confirmation.blade.php --}}
<h1>Thank you!</h1>
<p>Hello ${{ $donation->name }},</p>
<p>Just a quick email to acknowledge that your donation of <strong>${{ $donation->currency }}
        ${{ number_format($donation->amount, 2) }}</strong> has been
    received.</p>
<p>On behalf of "CATAWOL Records", thank you for your support!</p>
<p>&ndash; Drew (SilentMode)</p>
