import { Head, router } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Act } from '@/types';
import { ActItem } from '@/components/act-item';
import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { useEffect, useState } from 'react';

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

                <Dialog aria-describedby={undefined} open={showCurrentAct}
                        onOpenChange={() => setShowCurrentAct(false)}>
                    <DialogContent className="lg:max-w-3xl">
                        <div className="flex gap-5">
                            <PlaceholderPattern
                                className="w-1/3 aspect-square stroke-neutral-900/20 dark:stroke-neutral-100/20"/>
                            <div className="w-2/3">
                                <DialogTitle className="text-2xl font-semibold">Profile
                                    for {currentAct?.name}</DialogTitle>
                                <div>{currentAct?.profile?.description}</div>
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
