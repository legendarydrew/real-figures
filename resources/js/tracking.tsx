import ReactGA from 'react-ga4';
import { UaEventOptions } from 'react-ga4/types/ga4';

const analytics = {
    testMode: document.querySelector('meta[name=analytics-testing]').getAttribute('content'),
    measurementId: document.querySelector('meta[name=analytics-id]').getAttribute('content'),
    defaultCategory: document.querySelector('meta[name=analytics-event-category]').getAttribute('content')
};

const initialise = () => {
    ReactGA.initialize(analytics.measurementId, { testMode: !!Number.parseInt(analytics.testMode) });
    if (analytics.testMode) {
        console.log('Analytics initialised.');
    }
    trackPageView();
};

// Track page views.
const trackPageView = (path?: string): void => {
    path = path ?? window.location.pathname;
    ReactGA.send({ hitType: "pageview", page: path });
    if (analytics.testMode) {
        console.log('track page view', path);
    }
};

globalThis.trackEvent = (event: UaEventOptions) => {
    event.category = event.category ?? analytics.defaultCategory;
    ReactGA.event({
        ...event,
        nonInteraction: true, // optional, true/false
        transport: "xhr"
    });
    if (analytics.testMode) {
        console.log('trackEvent', event);
    }
}

initialise();
