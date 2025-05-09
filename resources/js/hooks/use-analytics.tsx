import ReactGA from "react-ga4";
import { UaEventOptions } from 'react-ga4/types/ga4';
import { usePage } from '@inertiajs/react';
import { useRef } from 'react';

// My attempt at creating a hook for tracking events in Google Analytics.

export function useAnalytics() {
    const { analytics } = usePage().props;

    const wasInitialised = useRef(false);

    const initialise = () => {
        if (!wasInitialised.current) {
            ReactGA.initialize(analytics.measurement_id, { testMode: analytics.testMode });
            wasInitialised.current = true;
        }
    };

    // Track page views.
    const trackPageView = (path?: string): void => {
        path = path ?? window.location.pathname;
        ReactGA.send({ hitType: "pageview", page: path });
        if (analytics.testMode) {
            console.log('track page view', path);
        }
    };

    const trackEvent = (event: UaEventOptions) => {
        ReactGA.event({
            ...event,
            nonInteraction: true, // optional, true/false
            transport: "xhr"
        });
        if (analytics.testMode) {
            console.log('trackEvent', event);
        }
    }

    return { initialise, trackEvent, trackPageView } as const;
}
