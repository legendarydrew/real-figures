import { UaEventOptions } from 'react-ga4/types/ga4';
import ReactGA from 'react-ga4';

const analytics = {
    testMode: document.querySelector('meta[name=analytics-testing]').getAttribute('content'),
    measurementId: document.querySelector('meta[name=analytics-id]').getAttribute('content'),
    defaultCategory: document.querySelector('meta[name=analytics-event-category]').getAttribute('content')
};

const isTesting: boolean =  !!Number.parseInt(analytics.testMode);
const initialise = () => {
    (ReactGA.default ?? ReactGA).initialize(analytics.measurementId, {
        gaOptions: {
            debug_mode: isTesting
        },
    });
    if (analytics.testMode) {
        console.log('Analytics initialised.');
    }
    trackPageView();
};

// Track page views.
const trackPageView = (path?: string): void => {
    path = path ?? globalThis.location.pathname;
    (ReactGA.default ?? ReactGA).send({ hitType: "pageview", page: path, visitor_viewport: getViewportSize() });
    if (analytics.testMode) {
        console.log('track page view', path);
    }
};

const getViewportSize = (): string => {
    const width: number = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    const height: number = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    return `${width} × ${height}`;
}

globalThis.trackEvent = (event: UaEventOptions | string, params?: { [key: string]: string | number }) => {
    if (typeof event === 'string') {
        // The recommended way (but requires the event to be set up as a custom dimension in GA4).
        (ReactGA.default ?? ReactGA).event(event, params);
    } else {
        // The old way...
        event.category = event.category ?? analytics.defaultCategory;
        (ReactGA.default ?? ReactGA).event({
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
