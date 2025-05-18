import { FrontHeader } from '@/components/front/front-header';
import { FrontFooter } from '@/components/front/front-footer';
import { ComponentProps, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { DonateDialog } from '@/components/front/donate-dialog';
import { DialogProvider } from '@/context/dialog-context';
import { useAnalytics } from '@/hooks/use-analytics';
import { FlashMessage } from '@/components/flash-message';

// see https://inertiajs.com/pages#persistent-layouts

export default function FrontLayout({ children }: ComponentProps<never>) {
    const { flash } = usePage().props;

    const { initialise, trackPageView, trackEvent } = useAnalytics();

    // Track a page view for the current page.
    // We can listen for changes to the URL to do so (which feels hacky).
    // We will also use track a non-interaction event if details were passed from the back end.
    useEffect(() => {
        initialise();
        trackPageView();

        if (flash?.track) {
            trackEvent({ nonInteraction: true, ...flash.track });
        }

    }, [window.location.pathname]);

    return (
        <DialogProvider>
            <div
                className="flex h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">
                <FrontHeader/>
                <main className="flex-grow w-full overflow-y-auto" scroll-region="">
                    {flash?.message && (
                        <FlashMessage message={flash.message}/>
                    )}
                    {children}
                </main>
                <FrontFooter/>

                <DonateDialog/>
            </div>
        </DialogProvider>
    );
}
