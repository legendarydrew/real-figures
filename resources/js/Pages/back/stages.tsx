import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { StageDialog } from '@/components/admin/stage-dialog';
import { useState } from 'react';
import { Stage } from '@/types';
import { DeleteStageDialog } from '@/components/admin/delete-stage-dialog';
import { StageItem } from '@/components/admin/stage-item';

export default function Stages({ stages }: Readonly<{ stages: Stage[] }>) {

    const [currentStage, setCurrentStage] = useState<Stage>();
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);

    const expandStageHandler = (stage: Stage): void => {
        // Fetch information about the specified Stage's Rounds.
        // This is how we can do it the Inertia way!
        // https://stackoverflow.com/a/77048332/4073160
        router.visit(route('stages.rounds', { id: stage.id }), {
            only: ['rounds'],
            replace: false,
            preserveState: true,
            preserveUrl: true,
            preserveScroll: true,
            onSuccess: ((page) => {
                stage.rounds = page.props.rounds;
            })
        });
    };

    const editHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsEditDialogOpen(true);
    }

    const deleteHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsDeleteDialogOpen(true);
    }

    return (
        <AppLayout>
            <Head title="Stages"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Stages</h1>
                <div className="flex gap-1">
                    <Button onClick={editHandler}>Create Stage</Button>
                </div>
            </div>

            <div className="p-4">
                {stages.data.map((stage) => (
                    <StageItem key={stage.id} onEdit={editHandler} onDelete={deleteHandler} stage={stage}/>
                ))}
            </div>

            <StageDialog stage={currentStage} open={isEditDialogOpen} onOpenChange={() => setIsEditDialogOpen(false)}/>
            <DeleteStageDialog stage={currentStage} open={isDeleteDialogOpen}
                               onOpenChange={() => setIsDeleteDialogOpen(false)}/>
        </AppLayout>
    );
}
