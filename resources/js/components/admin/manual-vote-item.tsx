import { Round } from '@/types';
import { ActImage } from '@/components/mode/act-image';
import React from 'react';
import { LanguageFlag } from '@/components/mode/language-flag';

interface ManualVoteProps {
    round: Round;
    votes: { [key: string]: number };
    positionErrors?: string[];
    onChange: (votes: { [key: string]: number }) => void;
}

export const ManualVoteItem: React.FC<ManualVoteProps> = ({
                                                              round, positionErrors, votes, onChange = () => {
    }
                                                          }) => {

    const positions = ['first', 'second', 'third'];

    const positionHasError = (position: string): boolean => {
        return positionErrors?.includes(position) ?? false;
    }

    const isChecked = (song_id: number, position: string): boolean => {
        return votes[position] === song_id;
    };

    const voteHandler = (song_id: number, position: string): void => {
        const updatedVotes = { ...votes };
        updatedVotes[position] = song_id;
        onChange(updatedVotes);
    }

    return (
        <>
            <div className="flex gap-2">
                <span className="flex-grow"></span>
                {positions.map((position) => (
                    <span key={position}
                          className={`w-12 text-xs font-bold text-center ${positionHasError(position) ? 'text-red-600' : ''}`}>
                        {position[0].toUpperCase() + position.slice(1)}
                    </span>
                ))}
            </div>
            <ul>
                {round.songs!.map((song) => (
                    <li key={song.id}
                        className="flex gap-2 items-center select-none hover:bg-indigo-100/50">
                        <ActImage act={song.act} className="h-10 mr-3"/>
                        <span className="w-80 display-text">
                            {song.act.name}
                            {song.act.subtitle && (
                                <small className="ml-0.5 text-muted-foreground">{song.act.subtitle}</small>)}
                        </span>
                        <div className="flex gap-2 items-center mr-auto display-text">
                            <LanguageFlag languageCode={song.language} />
                            {song.title}
                        </div>
                        {positions.map((position) => (
                            <label key={`${song.id}-${position}`}
                                   className={`table-cell w-12 text-center p-2 hover:bg-indigo-100 ${positionHasError(position) ? 'bg-red-100' : ''}`}
                                   aria-label={`${position} place`}>
                                <input type="radio" name={`${round.id}-${position}`}
                                       defaultChecked={isChecked(song.id, position)}
                                       onClick={() => voteHandler(song.id, position)}/>
                            </label>
                        ))}
                    </li>
                ))}
            </ul>
        </>)
};
