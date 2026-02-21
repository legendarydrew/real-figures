import { createRoot } from "react-dom/client";
import { CountdownTimer } from '@/components/mode/countdown-timer';
import { SubscribeForm } from '@/components/front/subscribe-form';
import { DonateDialog } from '@/components/front/donate-dialog';
import ContactForm from '@/components/contact-form';

let tags;

// Countdown.
tags = document.getElementsByTagName("countdown");
for (const element: HTMLElement of tags) {
    const timestamp = element.attributes.getNamedItem('timestamp').value;
    const size = element.attributes.getNamedItem('size')?.value;
    createRoot(element).render(<CountdownTimer timestamp={timestamp} size={size}/>);
}

// Subscribe form.
tags = document.getElementsByTagName("subscribe-form");
for (const element: HTMLElement of tags) {
    createRoot(element).render(<SubscribeForm/>);
}

// Donation dialog form.
tags = document.getElementsByTagName("donate-dialog");
for (const element: HTMLElement of tags) {
    createRoot(element).render(<DonateDialog/>);
}

// Contact form.
tags = document.getElementsByTagName("contact-form");
for (const element: HTMLElement of tags) {
    createRoot(element).render(<ContactForm/>);
}
