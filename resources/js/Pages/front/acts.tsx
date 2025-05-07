import { Head, router } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Act } from '@/types';
import { ActItem } from '@/components/act-item';
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { useEffect, useState } from 'react';
import { ActImage } from '@/components/ui/act-image';

interface ActsPageProps {
    acts?: Act[];
    currentAct?: Act;
}

const ActsPage: React.FC<ActsPageProps> = ({ acts, currentAct }) => {

    const [showCurrentAct, setShowCurrentAct] = useState<boolean>(false);

    useEffect(() => {
        setShowCurrentAct(!!currentAct);
    }, [currentAct]);

    const showActHandler = (act): void => {
        router.visit(route('act', { slug: act.slug }), {
            only: ['currentAct'],
            preserveUrl: true,
            onSuccess: () => {
                setShowCurrentAct(true);
            },
            onError: () => {
                setShowCurrentAct(false);
            }
        });
    };

    return (
        <>
            <Head title="Acts"/>

            <FrontContent>
                <Heading title="Competing Acts"/>

                {acts?.length ? (
                    <div className="grid auto-rows-min gap-1 md:grid-cols-3 lg:grid-cols-4">
                        {acts.map((act: Act) => (
                            <ActItem key={act.id} act={act} className={act.has_profile ? 'cursor-pointer' : ''}
                                     onClick={() => showActHandler(act)}/>
                        ))}
                    </div>
                ) : (
                    <Nothing>No Acts have entered the contest!</Nothing>
                )
                }

                <Dialog open={showCurrentAct}
                        onOpenChange={() => setShowCurrentAct(false)}>
                    <DialogContent className="lg:max-w-3xl">
                        <DialogDescription className="sr-only">Information about {currentAct?.name}.</DialogDescription>
                        <div className="flex gap-5">
                            <div className="w-1/3">
                                <ActImage act={currentAct} size="full"/>
                            </div>
                            <div className="w-2/3">
                                <DialogTitle className="text-2xl font-semibold mb-2">{currentAct?.name}</DialogTitle>
                                <div className="h-[50dvh] overflow-y-auto text-sm leading-normal"
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
