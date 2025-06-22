import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { StageDialog } from '@/components/admin/stage-dialog';
import React, { useState } from 'react';
import { Stage } from '@/types';
import { StageItem } from '@/components/admin/stage-item';
import { RoundAllocateDialog } from '@/components/admin/round-allocate-dialog';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import { DialogTitle } from '@/components/ui/dialog';
import { Toaster } from '@/components/mode/toast-message';
import { StageWinnersDialog } from '@/components/admin/stage-winners-dialog';
import { StageResultsDialog } from '@/components/admin/stage-results-dialog';
import { StageVotesDialog } from '@/components/admin/stage-votes-dialog';
import { Nothing } from '@/components/mode/nothing';

export default function Stages({ stages, songs }: Readonly<{ stages: Stage[], songs }>) {

    const [currentStage, setCurrentStage] = useState<Stage>();
    const [isAllocateDialogOpen, setIsAllocateDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);
    const [isDeleting, setIsDeleting] = useState<boolean>(false);
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isStageResultsDialogOpen, setIsStageResultsDialogOpen] = useState<boolean>(false);
    const [isStageVotesDialogOpen, setIsStageVotesDialogOpen] = useState<boolean>(false);
    const [isWinnerDialogOpen, setIsWinnerDialogOpen] = useState<boolean>(false);

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

    const chooseWinnerHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsWinnerDialogOpen(true);
    }

    const showResultsHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsStageResultsDialogOpen(true);
    }

    const showVotesHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsStageVotesDialogOpen(true);
    }

    const editHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsEditDialogOpen(true);
    }

    const deleteHandler = (stage: Stage) => {
        setCurrentStage(stage);
        setIsDeleteDialogOpen(true);
    }

    const confirmDeleteHandler = () => {
        if (currentStage) {
            router.delete(route('stages.destroy', { id: currentStage.id }), {
                preserveUrl: true,
                preserveScroll: true,
                onStart: () => {
                    setIsDeleting(true);
                },
                onFinish: () => {
                    setIsDeleting(false);
                },
                onSuccess: () => {
                    Toaster.success(`"${currentStage.title}" was deleted.`);
                    setIsDeleteDialogOpen(false);
                    setCurrentStage(undefined);
                }
            });
        }
    };

    return (
        <AppLayout>
            <Head title="Stages"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Stages</h1>
                <div className="flex gap-1">
                    <Button onClick={editHandler}>Create Stage</Button>
                </div>
            </div>

            <div className="p-4">
                {stages.length ? stages.map((stage) => (
                    <StageItem key={stage.id} onAllocate={allocateHandler} onEdit={editHandler}
                               onDelete={deleteHandler} onChooseWinner={chooseWinnerHandler}
                               onShowResults={showResultsHandler}
                               onShowVotes={showVotesHandler}
                               stage={stage}/>
                )) : (
                    <Nothing>
                        No Stages present.
                    </Nothing>
                )}
            </div>

            <StageDialog stage={currentStage} open={isEditDialogOpen} onOpenChange={() => setIsEditDialogOpen(false)}/>
            <RoundAllocateDialog stage={currentStage} open={isAllocateDialogOpen} songs={songs}
                                 onOpenChange={() => setIsAllocateDialogOpen(false)}/>
            <StageWinnersDialog stage={currentStage} open={isWinnerDialogOpen}
                                onOpenChange={() => setIsWinnerDialogOpen(false)}/>
            <StageResultsDialog stage={currentStage} open={isStageResultsDialogOpen}
                                onOpenChange={() => setIsStageResultsDialogOpen(false)}/>
            <StageVotesDialog stage={currentStage} open={isStageVotesDialogOpen}
                              onOpenChange={() => setIsStageVotesDialogOpen(false)}/>
            <DestructiveDialog open={isDeleteDialogOpen} onOpenChange={() => setIsDeleteDialogOpen(false)}
                               onConfirm={confirmDeleteHandler} processing={isDeleting}>
                <DialogTitle>{`Delete Stage "${currentStage?.title}"`}</DialogTitle>

                <span className="italic">This will also delete the Rounds associated with this Stage.</span><br/>
                Are you sure you want to do this?
            </DestructiveDialog>
        </AppLayout>
    );
}
