import { Stage } from '@/types';
import { Button } from '@/components/ui/button';
import { ChevronDown, Edit, Trash } from 'lucide-react';
import React from 'react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { StageStatusTag } from '@/components/ui/stage-status-tag';
import { StageRoundItem } from '@/components/admin/stage-round-item';
import { router } from '@inertiajs/react';
import toast from 'react-hot-toast';

interface StageItemProps {
    stage: Stage;
    onAllocate?: (stage: Stage) => void;
    onEdit?: (stage: Stage) => void;
    onDelete?: (stage: Stage) => void;
}

export const StageItem: React.FC<StageItemProps> = ({ stage, onAllocate, onEdit, onDelete }) => {

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


    return (
        <Collapsible className="mb-2">
            <div
                className="flex gap-2 py-2 px-3 b-2 w-full bg-gray-200 hover:bg-gray-300 items-center justify-between">
                <span className="flex-grow font-bold text-left">{stage.title}</span>
                <StageStatusTag stage={stage}/>
                <Button type="button" className="p-3 cursor-pointer"
                        onClick={allocateHandler}>
                    Allocate
                </Button>
                {stage.status.manual_vote && <Button type="button" variant="secondary" className="p-3 cursor-pointer"
                                                     onClick={manualVoteHandler}>
                    Manual Vote
                </Button>}
                <Button type="button" variant="secondary" className="p-3 cursor-pointer"
                        onClick={editHandler}
                        title="Edit Stage">
                    <Edit className="h-3 w-3"/>
                </Button>
                <Button type="button" variant="destructive" className="p-3 cursor-pointer"
                        onClick={deleteHandler}
                        title="Delete Stage">
                    <Trash className="h-3 w-3"/>
                </Button>
                <CollapsibleTrigger className="p-3 hover:bg-gray-400 cursor-pointer" title="Expand">
                    <ChevronDown className="h-3 w-3"/>
                </CollapsibleTrigger>
            </div>
            <CollapsibleContent className="bg-gray-100 p-3">

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
