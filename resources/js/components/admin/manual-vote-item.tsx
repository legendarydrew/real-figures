import { Round } from '@/types';
import { ActImage } from '@/components/ui/act-image';
import React from 'react';

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
                        <span className="w-[20em]">{song.act.name}</span>
                        <span className="mr-auto">{song.title}</span>
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
