import { Round } from '@/types';
import React from 'react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { LanguageFlag } from '@/components/language-flag';
import { ActImage } from '@/components/ui/act-image';

interface StageItemProps {
    round: Round;
}

export const StageRoundItem: React.FC<StageItemProps> = ({ round }) => {

    return round && (
        <Collapsible className="mb-0.5">
            <CollapsibleTrigger
                className="flex gap-1 px-3 b-2 w-full bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 items-center justify-between">
                <span className="flex-grow display-text text-left py-2">{round.title}</span>
                <div className="inline-flex gap-0.5">
                    {round.songs?.map((song) => (
                        <ActImage key={song.id} act={song.act} size="10 "/>
                    ))}
                </div>
                <span className="w-[9em] py-2 text-right text-sm">{round.vote_count} vote(s)</span>
                <span className="w-[10em] py-2 text-center text-xs">{round.starts_at}</span>
                <span className="text-xs py-2 text-muted-foreground">to</span>
                <span className="w-[10em] py-2 text-center text-xs">{round.ends_at}</span>
            </CollapsibleTrigger>
            <CollapsibleContent className="p-1">
                {/* Display information about Songs in this Round here. */}
                <ul>
                    {round.songs?.map((song) => (
                        <li key={song.id}
                            className="my-0.5 flex gap-1 justify-between items-center text-sm hover:bg-indigo-100 dark:hover:bg-indigo-800 select-none">
                            <ActImage className="w-10 h-10" act={song.act}/>
                            <span className="w-[20em] display-text">{song.act.name}</span>
                            <span className="mr-auto display-text flex gap-1 items-center">
                                <LanguageFlag languageCode={song.language}/>
                                {song.title}
                            </span>
                            <span className="pr-2 text-right">
                                {song.play_count.toLocaleString()}
                                <span className="text-xs ml-1">{ song.play_count === 1 ? 'play' : 'plays'}</span>
                            </span>
                        </li>
                    ))}
                </ul>
            </CollapsibleContent>
        </Collapsible>
    );
};
