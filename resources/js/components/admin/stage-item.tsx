import { Stage } from '@/types';
import { Button } from '@/components/ui/button';
import { Award, Boxes, ChevronDown, Edit, FileBadge, Trash, Vote } from 'lucide-react';
import React from 'react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { StageStatusTag } from '@/components/ui/stage-status-tag';
import { StageRoundItem } from '@/components/admin/stage-round-item';
import { router } from '@inertiajs/react';
import toast from 'react-hot-toast';

interface StageItemProps {
    stage: Stage;
    onAllocate?: (stage: Stage) => void;
    onChooseWinner?: (stage: Stage) => void;
    onDelete?: (stage: Stage) => void;
    onEdit?: (stage: Stage) => void;
    onShowResults?: (stage: Stage) => void;
    onShowVotes?: (stage: Stage) => void;
}

export const StageItem: React.FC<StageItemProps> = ({
                                                        stage,
                                                        onAllocate,
                                                        onChooseWinner,
                                                        onEdit,
                                                        onDelete,
                                                        onShowResults,
                                                        onShowVotes
                                                    }) => {

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

    const showResultsHandler = (): void => {
        if (onShowResults) {
            onShowResults(stage);
        }
    }

    const showVotesHandler = (): void => {
        if (onShowVotes) {
            onShowVotes(stage);
        }
    }


    return (
        <Collapsible className="mb-2">
            <div
                className="flex gap-2 py-0 pl-3 b-2 w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 items-center justify-between">
                <span className="display-text flex-grow text-left">{stage.title}</span>
                <StageStatusTag stage={stage}/>
                <div className="toolbar">
                    {!stage.status.has_started && (<Button type="button" className="p-3 cursor-pointer"
                                                           onClick={allocateHandler} title="Allocate Songs to Stage">
                        <Boxes/>
                    </Button>)}
                    {stage.status.manual_vote && <Button type="button" variant="outline" className="p-3 cursor-pointer"
                                                         onClick={manualVoteHandler} title="Manual Voting">
                        <Vote/>
                    </Button>}
                    {stage.status.choose_winners &&
                        <Button type="button" variant="default" className="p-3 cursor-pointer"
                                onClick={chooseWinnerHandler} title="Choose Winning Songs">
                            <FileBadge/>
                        </Button>}
                    {(stage.status?.has_ended && !stage.status.manual_vote) &&
                        <Button type="button" variant="default" className="p-3 cursor-pointer"
                                onClick={showVotesHandler} title="Vote Breakdown">
                            <Vote/>
                        </Button>}
                    {stage.winners.length ? (
                        <Button type="button" variant="default" className="p-3 cursor-pointer"
                                onClick={showResultsHandler}>
                            <Award/>
                        </Button>) : ''}
                </div>
                <div className="toolbar">
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
                </div>
                <CollapsibleTrigger className="p-3 hover:bg-gray-400 cursor-pointer" title="Expand">
                    <ChevronDown/>
                </CollapsibleTrigger>
            </div>
            <CollapsibleContent className="bg-gray-100 dark:bg-gray-600 p-3">

                <div className="mt-2 mb-5 text-sm">
                    {stage.description}
                </div>

                {stage.rounds.map((round) => (
                    <StageRoundItem key={round.id} round={round}/>
                ))}
            </CollapsibleContent>
        </Collapsible>
    );
};
