import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { StageDialog } from '@/components/admin/stage-dialog';
import { useState } from 'react';
import { Stage } from '@/types';
import { DeleteStageDialog } from '@/components/admin/delete-stage-dialog';
import { StageItem } from '@/components/admin/stage-item';
import { RoundAllocateDialog } from '@/components/admin/round-allocate-dialog';

export default function Stages({ stages, songs }: Readonly<{ stages: Stage[], songs }>) {

    const [currentStage, setCurrentStage] = useState<Stage>();
    const [isAllocateDialogOpen, setIsAllocateDialogOpen] = useState<boolean>(false);
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);

    const allocateHandler = (stage: Stage): void => {
        setCurrentStage(stage);

        // Fetch a list of Songs before opening the allocation dialog.
        router.reload({
            only: ['songs', 'roundConfig'],
            showProgress: true,
            onSuccess: () => {
                setIsAllocateDialogOpen(true);
            }
        });
    }

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
                    <StageItem key={stage.id} onAllocate={allocateHandler} onEdit={editHandler} onDelete={deleteHandler}
                               stage={stage}/>
                ))}
            </div>

            <StageDialog stage={currentStage} open={isEditDialogOpen} onOpenChange={() => setIsEditDialogOpen(false)}/>
            <RoundAllocateDialog stage={currentStage} open={isAllocateDialogOpen} songs={songs}
                                 onOpenChange={() => setIsAllocateDialogOpen(false)}/>
            <DeleteStageDialog stage={currentStage} open={isDeleteDialogOpen}
                               onOpenChange={() => setIsDeleteDialogOpen(false)}/>
        </AppLayout>
    );
}
