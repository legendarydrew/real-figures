import { Stage } from '@/types';
import { Button } from '@/components/ui/button';
import { Boxes, ChevronDown, Edit, Trash, Trophy, Vote } from 'lucide-react';
import React from 'react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { StageStatusTag } from '@/components/ui/stage-status-tag';
import { StageRoundItem } from '@/components/admin/stage-round-item';
import { router } from '@inertiajs/react';
import toast from 'react-hot-toast';
import { ActImage } from '@/components/ui/act-image';

interface StageItemProps {
    stage: Stage;
    onAllocate?: (stage: Stage) => void;
    onChooseWinner?: (stage: Stage) => void;
    onDelete?: (stage: Stage) => void;
    onEdit?: (stage: Stage) => void;
}

export const StageItem: React.FC<StageItemProps> = ({ stage, onAllocate, onChooseWinner, onEdit, onDelete }) => {

    const allocateHandler = (): void => {
        if (onAllocate) {
            onAllocate(stage);
        }
    }

    const manualVoteHandler = () => {
        router.get(route('stages.manual-vote.show', { id: stage.id }), {}, {
            onError: (response) => {
                toast.error(response[0]);
            }
        });
    }

    const editHandler = (): void => {
        if (onEdit) {
            onEdit(stage);
        }
    }

    const deleteHandler = (): void => {
        if (onDelete) {
            onDelete(stage);
        }
    }

    const chooseWinnerHandler = (): void => {
        if (onChooseWinner) {
            onChooseWinner(stage);
        }
    }


    return (
        <Collapsible className="mb-2">
            <div
                className="flex gap-2 py-2 px-3 b-2 w-full bg-gray-200 items-center justify-between">
                <span className="flex-grow font-bold text-left">{stage.title}</span>
                <StageStatusTag stage={stage}/>
                {!stage.status?.has_started && (<Button type="button" className="p-3 cursor-pointer"
                                                        onClick={allocateHandler} title="Allocate Songs to Stage">
                    <Boxes/>
                </Button>)}
                {stage.status.manual_vote && <Button type="button" variant="default" className="p-3 cursor-pointer"
                                                     onClick={manualVoteHandler} title="Manual Voting">
                    <Vote/>
                </Button>}
                {stage.status.choose_winners && <Button type="button" variant="default" className="p-3 cursor-pointer"
                                                        onClick={chooseWinnerHandler}>
                    <Trophy/>
                </Button>}
                <Button type="button" variant="secondary" className="p-3 cursor-pointer"
                        onClick={editHandler}
                        title="Edit Stage">
                    <Edit/>
                </Button>
                <Button type="button" variant="destructive" className="p-3 cursor-pointer"
                        onClick={deleteHandler}
                        title="Delete Stage">
                    <Trash/>
                </Button>
                <CollapsibleTrigger className="p-3 hover:bg-gray-400 cursor-pointer" title="Expand">
                    <ChevronDown/>
                </CollapsibleTrigger>
            </div>
            <CollapsibleContent className="bg-gray-100 p-3">

                <div className="mt-2 mb-5 text-sm">
                    {stage.description}
                </div>

                {stage.winners.length ? (
                    <>
                        <h3 className="font-bold mb-2">Winning Songs</h3>
                        <ul className="text-sm mb-3">
                            {stage.winners.map((winner) => (
                                <li key={winner.id} className="flex justify-between items-center gap-2">
                                    <ActImage act={winner.song.act}/>
                                    <span className="font-bold">{winner.song.act.name}</span>
                                    <span className="mr-auto">{winner.song.title}</span>
                                    <span className="text-right">{winner.is_winner ? 'Winner' : 'Runner-up'}</span>
                                    <span className="text-right">{winner.round}</span>
                                </li>
                            ))}
                        </ul>
                    </>
                ) : ''}

                {stage.rounds.map((round) => (
                    <StageRoundItem key={round.id} round={round}/>
                ))}
            </CollapsibleContent>
        </Collapsible>
    );
};
