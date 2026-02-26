import { createRoot } from "react-dom/client";
import { CountdownTimer } from '@/components/mode/countdown-timer';
import { SubscribeForm } from '@/components/front/subscribe-form';
import { DonateDialog } from '@/components/front/donate-dialog';
import ContactForm from '@/components/contact-form';
import { RoundVoteDialog } from '@/components/front/round-vote-dialog';
import { GoldenBuzzerDialog } from '@/components/front/golden-buzzer-dialog';

import.meta.glob([
    '../../public/img/**'
]);

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

// Vote dialog.
tags = document.getElementsByTagName("vote-dialog");
for (const element: HTMLElement of tags) {
    const round = element.attributes.getNamedItem('round').value;
    createRoot(element).render(<RoundVoteDialog round={JSON.parse(round)}/>);
}

// Golden Buzzer dialog.
tags = document.getElementsByTagName("golden-buzzer-dialog");
for (const element: HTMLElement of tags) {
    const stage = element.attributes.getNamedItem('stage').value;
    const round = element.attributes.getNamedItem('round').value;
    const song = element.attributes.getNamedItem('song').value;
    createRoot(element).render(<GoldenBuzzerDialog stage={JSON.parse(stage)} round={JSON.parse(round)}
                                                   song={JSON.parse(song)}/>);
}

// If there is a hash in the current URL, scroll to the location of the respective element (if there is one).
// This is more for the benefit of linking to collapse sections.
const hash = window.location.hash;
if (hash) {
    const element = document.getElementById(hash.replace('#', ''));
    const menu = document.querySelector('header.site-header');
    const checkbox = element.parentElement.querySelector('input[type="checkbox"]');
    if (checkbox) {
        checkbox.setAttribute('checked', 'checked'); // open the collapse section.
    }
    if (element) {
        window.scrollTo({
            behavior: 'smooth',
            top: element.getBoundingClientRect().top + window.scrollY - menu.clientHeight - 24
        });
    }
}
