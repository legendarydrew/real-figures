import ReactGA from 'react-ga4';
import { UaEventOptions } from 'react-ga4/types/ga4';

const analytics = {
    testMode: document.querySelector('meta[name=analytics-testing]').getAttribute('content'),
    measurementId: document.querySelector('meta[name=analytics-id]').getAttribute('content'),
    defaultCategory: document.querySelector('meta[name=analytics-event-category]').getAttribute('content')
};

const isTesting: boolean =  !!Number.parseInt(analytics.testMode);
const initialise = () => {
    ReactGA.initialize(analytics.measurementId, {
        gaOptions: {
            debug_mode: isTesting
        },
        // testMode: isTesting
    });
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

globalThis.trackEvent = (event: UaEventOptions | string, params?: { [key: string]: string | number }) => {
    if (typeof event === 'string') {
        // The recommended way (but requires the event to be set up as a custom dimension in GA4).
        ReactGA.event(event, params);
    } else {
        // The old way...
        event.category = event.category ?? analytics.defaultCategory;
        ReactGA.event({
            ...event,
            nonInteraction: true, // optional, true/false
            transport: "xhr"
        });
    }
    if (analytics.testMode) {
        console.log('trackEvent', event, params);
    }
}

initialise();
