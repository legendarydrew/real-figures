import { Round } from '@/types';
import React from 'react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { LanguageFlag } from '@/components/mode/language-flag';
import { ActImage } from '@/components/mode/act-image';
import { ChevronDown, GavelIcon, MicrochipIcon, PersonStandingIcon } from 'lucide-react';

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
                <span className="w-[10em] py-2 text-center text-xs">{round.starts_at}</span>
                <span className="text-xs py-2 text-muted-foreground">to</span>
                <span className="w-[10em] py-2 text-center text-xs">{round.ends_at}</span>
                <ChevronDown/>
            </CollapsibleTrigger>
            <CollapsibleContent className="p-4">
                {/* Display information about Songs in this Round here. */}
                <ul className="grid md:cols-2 lg:grid-cols-4 gap-4 mb-4">
                    {round.songs?.map((song) => (
                        <li key={song.id} className="flex gap-2 items-center text-sm select-none">
                            <ActImage className="size-10" act={song.act}/>
                            <div className="flex flex-col leading-none">
                                <span className="display-text leading-none">
                                    {song.act.name} <small className="text-muted-foreground">{song.act.subtitle}</small>
                                </span>
                                <span className="display-text flex gap-1 items-center">
                                    <LanguageFlag languageCode={song.language}/>
                                    {song.title}
                                </span>
                            </div>
                        </li>
                    ))}
                </ul>

                {/* Breakdown of votes (if any). */}
                { round.vote_count.total ? (
                    <div className="flex gap-8 items-center text-sm py-2 px-4 font-semibold rounded-sm bg-secondary/10 mt-2 leading-none">
                        <span className="font-bold flex-grow text-base">
                            { round.vote_count.total } { round.vote_count.total === 1 ? 'Vote' : 'Votes' }
                        </span>
                        <span className="text-right flex items-center gap-1" title="Organic votes">
                            <PersonStandingIcon className="size-4" />
                            { round.vote_count.public }
                        </span>
                        <span className="text-right flex items-center gap-1 text-indigo-700" title="Manual votes">
                            <GavelIcon className="size-4" />
                            { round.vote_count.manual }
                        </span>
                        <span className="text-right flex items-center gap-1 text-green-700" title="Dumbrick votes">
                            <MicrochipIcon className="size-4" />
                            { round.vote_count.dumbrick }
                        </span>
                    </div>
                    ) : (
                    <div className="nothing text-sm">No votes</div>
                )}

            </CollapsibleContent>
        </Collapsible>
    );
};
