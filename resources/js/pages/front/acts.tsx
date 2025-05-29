import { Head, router } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Act } from '@/types';
import { ActItem } from '@/components/act-item';
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { useEffect, useState } from 'react';
import { ActImage } from '@/components/ui/act-image';
import { useAnalytics } from '@/hooks/use-analytics';
import { Advert } from '@/components/advert';

interface ActsPageProps {
    acts?: Act[];
    currentAct?: Act;
}

const ActsPage: React.FC<ActsPageProps> = ({ acts, currentAct }) => {

    const [showCurrentAct, setShowCurrentAct] = useState<boolean>(false);

    const { trackEvent } = useAnalytics();

    useEffect(() => {
        setShowCurrentAct(!!currentAct);
    }, [currentAct]);

    const showActHandler = (act): void => {
        // Only display information about Acts that have a profile.
        if (act.has_profile) {
            router.visit(route('act', { slug: act.slug }), {
                only: ['currentAct'],
                preserveUrl: true,
                onSuccess: () => {
                    setShowCurrentAct(true);
                    trackEvent({ category: 'Act', action: 'View Profile', label: act.name, nonInteraction: false });
                },
                onError: () => {
                    setShowCurrentAct(false);
                }
            });
        }
    };

    return (
        <>
            <Head title="Acts"/>

            <FrontContent>
                <Heading className="mb-3" title="Competing Acts"/>

                <div className="lg:flex gap-3">
                    <div className="lg:w-3/4">

                        {acts?.length ? (
                            <div className="grid auto-rows-min gap-1 md:grid-cols-3 lg:grid-cols-4">
                                {acts.map((act: Act) => (
                                    <ActItem key={act.id} act={act} className={act.has_profile ? 'cursor-pointer' : ''}
                                             onClick={() => showActHandler(act)}/>
                                ))}
                            </div>
                        ) : (
                            <Nothing>No Acts have entered the contest - yet!</Nothing>
                        )
                        }
                    </div>
                    <div className="lg:w-1/4">
                        <Advert className="mx-auto h-[280px] md:h-[240px] text-center"/>
                    </div>
                </div>

                <Dialog open={showCurrentAct}
                        onOpenChange={() => setShowCurrentAct(false)}>
                    <DialogContent className="md:max-w-2xl lg:max-w-3xl">
                        <DialogDescription className="sr-only">Information about {currentAct?.name}.</DialogDescription>
                        <div
                            className="flex flex-col overflow-y-auto max-h-[80dvh] md:flex-row md:overflow-visible md:max-h-none gap-5">
                            <div className="md:w-1/3">
                                <ActImage act={currentAct} size="full"/>
                            </div>
                            <div className="md:w-2/3">
                                <DialogTitle className="text-2xl mb-2">{currentAct?.name}</DialogTitle>
                                <div className="content md:h-[50dvh] md:overflow-y-auto text-sm leading-normal"
                                     dangerouslySetInnerHTML={{ __html: currentAct?.profileContent?.description }}/>
                                {/* https://react.dev/reference/react-dom/components/common#dangerously-setting-the-inner-html */}
                            </div>
                        </div>
                    </DialogContent>
                </Dialog>
            </FrontContent>
        </>
    );
};

ActsPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default ActsPage;
