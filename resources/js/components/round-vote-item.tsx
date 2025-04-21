import { ActImage } from '@/components/ui/act-image';
import React from 'react';
import { Round } from '@/types';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/react';

interface RoundVoteProps {
    round: Round;
}

export const RoundVoteItem: React.FC<RoundVoteProps> = ({ round }) => {

        const { data, setData } = useForm({
            round_id: round.id,
            first_choice_id: 0,
            second_choice_id: 0,
            third_choice_id: 0
        });

        const positions = {
            first: '1st', second: '2nd', third: '3rd'
        };

        const isChecked = (song_id: number, position: string): boolean => {
            return data[`${position}_choice_id`] === song_id;
        };

        const voteHandler = (song_id: number, position: string): void => {
            setData(`${position}_choice_id`, song_id);

            // Make sure the song does not occupy any of the other positions.
            Object.keys(positions).forEach((p) => {
                if (p !== position && data[`${p}_choice_id`] === song_id) {
                    setData(`${p}_choice_id`, 0);
                }
            });
        }

        return (
            <ul>
                {round.songs!.map((song) => (
                    <li key={song.id}
                        className="flex gap-2 items-center select-none hover:bg-gray-100">
                        <ActImage act={song.act} className="h-10 mr-3"/>
                        <span className="flex-grow">{song.act.name}</span>
                        {Object.keys(positions).map((position) => (
                            <Button key={`${song.id}-${position}`} type="button"
                                    variant={isChecked(song.id, position) ? 'default' : 'outline'}
                                    className="rounded-none"
                                    aria-label={`Make ${song.act.name} your ${positions[position]} choice.`}
                                    onClick={() => voteHandler(song.id, position)}>{positions[position]}</Button>
                        ))}
                    </li>
                ))}
            </ul>
        )
    }
;
