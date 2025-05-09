import { FrontHeader } from '@/components/front/front-header';
import { FrontFooter } from '@/components/front/front-footer';
import { ComponentProps, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { DonateDialog } from '@/components/front/donate-dialog';
import { DialogProvider } from '@/context/dialog-context';
import { useAnalytics } from '@/hooks/use-analytics';

// see https://inertiajs.com/pages#persistent-layouts

export default function FrontLayout({ children }: ComponentProps<never>) {
    const { flash } = usePage().props;

    const { initialise, trackPageView } = useAnalytics();

    // Track a page view for the current page.
    // We can listen for changes to the URL (which feels hacky).
    useEffect(() => {
        initialise();
        trackPageView();
    }, [window.location.pathname]);

    return (
        <DialogProvider>
            <div
                className="flex h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">
                <FrontHeader/>
                <main className="flex-grow w-full overflow-y-auto">
                    {flash?.message && (
                        <div className="alert">{flash.message}</div>
                    )}
                    {children}
                </main>
                <FrontFooter/>

                <DonateDialog/>
            </div>
        </DialogProvider>
    );
}
