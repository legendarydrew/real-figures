import { createRoot } from "react-dom/client";
import { CountdownTimer } from '@/components/mode/countdown-timer';

const container = document.getElementsByTagName("countdown");
for (const element: HTMLElement of container) {
    const timestamp = element.attributes.getNamedItem('timestamp').value;
    const size = element.attributes.getNamedItem('size')?.value;
    createRoot(element).render(<CountdownTimer timestamp={timestamp} size={size}/>);
}
