import { ActImage } from '@/components/ui/act-image';
import { Round } from '@/types';
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/react';
import { LoadingButton } from '@/components/ui/loading-button';
import { FormEvent, useState } from 'react';
import axios from 'axios';

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

    const [error, setError] = useState<string | null>(null);
    const [processing, setProcessing] = useState<boolean>(false);
    const [successful, setSuccessful] = useState<boolean>(false);

        const isChecked = (song_id: number, position: string): boolean => {
            return data[`${position}_choice_id`] === song_id;
        };

    const choiceHandler = (song_id: number, position: string): void => {
            setData(`${position}_choice_id`, song_id);

            // Make sure the song does not occupy any of the other positions.
            Object.keys(positions).forEach((p) => {
                if (p !== position && data[`${p}_choice_id`] === song_id) {
                    setData(`${p}_choice_id`, 0);
                }
            });
        }

    const submitHandler = (e: FormEvent): void => {
        e.preventDefault();

        if (processing) {
            return;
        }

        // Using axios to make the request, because we're fetching a JSON response.
        setProcessing(true);
        setError(null);
        axios.post(route('vote'), data)
            .then(() => {
                setSuccessful(true);
            })
            .catch((response) => {
                setError(response.data.message);
            })
            .finally(() => {
                setProcessing(false);
            });
    };

    return successful ? (
        <div className="bg-green-100 text-green-600 p-3 text-center">Thank you for your vote!</div>
    ) : (
        <form onSubmit={submitHandler}>
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
                                    onClick={() => choiceHandler(song.id, position)}>{positions[position]}</Button>
                        ))}
                    </li>
                ))}
            </ul>
            {error && (
                <div className="text-destructive-foreground p-1">{error}</div>
            )}
            <div className="flex justify-end">
                <LoadingButton isLoading={processing} type="submit">Cast Vote!</LoadingButton>
            </div>
        </form>);
    }
;
