import { FrontHeader } from '@/components/front/front-header';
import { FrontFooter } from '@/components/front/front-footer';
import { ComponentProps, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { DonateDialog } from '@/components/front/donate-dialog';
import { DialogProvider } from '@/context/dialog-context';
import { useAnalytics } from '@/hooks/use-analytics';
import { FlashMessage } from '@/components/flash-message';
import { GoldenBuzzerDialog } from '@/components/front/golden-buzzer-dialog';
import { SongPlayer } from '@/components/front/song-player';
import { SongPlayerProvider } from '@/context/song-player-context';

// see https://inertiajs.com/pages#persistent-layouts

export default function FrontLayout({ children }: ComponentProps<never>) {
    const { flash, status } = usePage().props;

    const { initialise, trackPageView, trackEvent } = useAnalytics();

    // Track a page view for the current page.
    // We can listen for changes to the URL to do so (which feels hacky).
    // We will also use track a non-interaction event if details were passed from the back end.
    useEffect(() => {
        initialise();

        if (!status) {
            // We don't want to track invalid pages, indicated by a defined status in the page props.
            trackPageView();
        }

        if (flash?.track) {
            trackEvent({ nonInteraction: true, ...flash.track });
        }

    }, [window.location.pathname]);

    return (
        <DialogProvider>
            <div
                className="overflow-hidden h-full flex flex-col items-center bg-light text-dark lg:justify-center dark:bg-dark dark:text-dark-foreground">
                <FrontHeader/>
                <SongPlayerProvider>
                    <main className="flex-grow w-full overflow-y-auto" scroll-region="">
                        {flash?.message && (
                            <FlashMessage message={flash.message}/>
                        )}
                        {children}
                    </main>
                    <SongPlayer/>
                    <FrontFooter/>
                    <GoldenBuzzerDialog/>
                </SongPlayerProvider>
                <DonateDialog/>
            </div>
        </DialogProvider>
    );
}
