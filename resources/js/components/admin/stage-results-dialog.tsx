import React, { FC, useEffect } from 'react';
import { Dialog, DialogContent, DialogTitle } from '@/components/ui/dialog';
import { Stage, StageWinner } from '@/types';
import { ActImage } from '@/components/ui/act-image';
import toast from 'react-hot-toast';

interface StageResultsDialogProps {
    open: boolean;
    onOpenChange: () => void;
    stage?: Stage;
}

export const StageResultsDialog: FC<StageResultsDialogProps> = ({ open, onOpenChange, stage }) => {

    useEffect(() => {
        if (open && !stage?.winners) {
            onOpenChange();
            toast.error('This Stage is not ready yet.');
        }
    }, [open]);

    const winningRows = (): StageWinner[] => {
        return stage?.winners ? stage.winners.filter((row) => row.is_winner) : [];
    }

    const runnerUpRows = (): StageWinner[] => {
        return stage?.winners ? stage.winners.filter((row) => !row.is_winner) : [];
    }

    return stage && (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="lg:max-w-[40em]" aria-describedby={undefined}>
                <DialogTitle>{stage.title} Results</DialogTitle>

                <div className="overflow-y-auto h-[20rem]">
                    {winningRows().length ? (
                        <>
                            <h3 className="font-bold mb-1">Winning Songs</h3>
                            <ul className="mb-5 text-base">
                                {winningRows().map((winner) => (
                                    <li key={winner.id}
                                        className="flex pr-2 justify-between items-center gap-2 my-0.5 h-[4rem] hover:bg-gray-100">
                                        <ActImage act={winner.song.act}/>
                                        <div className="flex-grow flex flex-col">
                                            <span className="font-bold">{winner.song.act.name}</span>
                                            <span className="text-sm">{winner.song.title}</span>
                                        </div>
                                        <span className="text-sm text-right text-muted-foreground">{winner.round}</span>
                                    </li>
                                ))}
                            </ul>
                        </>
                    ) : ''}

                    {runnerUpRows().length ? (
                        <>
                            <h3 className="font-bold mb-1 text-base">Highest-scoring runners-up</h3>
                            <ul className="mb-3 text-sm">
                                {runnerUpRows().map((winner) => (
                                    <li key={winner.id}
                                        className="flex pr-3 justify-between items-center gap-2 my-0.5 hover:bg-gray-100">
                                        <ActImage act={winner.song.act}/>
                                        <div className="flex-grow flex flex-row gap-1">
                                            <span className="font-bold">{winner.song.act.name}</span>
                                            <span className="mr-auto">{winner.song.title}</span>
                                        </div>
                                        <span className="text-right text-muted-foreground">{winner.round}</span>
                                    </li>
                                ))}
                            </ul>
                        </>
                    ) : ''}
                </div>

            </DialogContent>
        </Dialog>
    )
}
