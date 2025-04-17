import { Round } from '@/types';
import React from 'react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';

interface StageItemProps {
    round: Round;
}

export const StageRoundItem: React.FC<StageItemProps> = ({ round }) => {

    return round && (
        <Collapsible className="mb-0.5">
            <CollapsibleTrigger
                className="flex gap-1 py-2 px-3 b-2 w-full bg-blue-100 hover:bg-blue-200 items-center justify-between">
                <span className="flex-grow font-bold text-left">{round.title}</span>
                <span className="w-[12em] text-center text-sm">{round.starts_at}</span>
                <span className="text-sm">to</span>
                <span className="w-[12em] text-center text-sm">{round.ends_at}</span>
                {/* small Act images to go here. */}
            </CollapsibleTrigger>
            <CollapsibleContent className="p-3">
                {/* Display information about Songs in this Round here. */}
                ...
            </CollapsibleContent>
        </Collapsible>
    );
};
