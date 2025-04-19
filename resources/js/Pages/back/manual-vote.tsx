import AppLayout from '@/layouts/app-layout';
import { Head, router, useForm } from '@inertiajs/react';
import React from 'react';
import { Round } from '@/types';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { ActImage } from '@/components/ui/act-image';
import { LoadingButton } from '@/components/ui/loading-button';
import { Button } from '@/components/ui/button';
import { CircleAlert } from 'lucide-react';

interface ManualVotePageProps {
    stage: {
        id: number;
        title: string;
    };
    rounds: Round[];
}

type ManualVoteForm = {
    votes: {
        round_id: number;
        song_ids: {
            first: number;
            second: number;
            third: number;
        }
    }[];
}


export default function ManualVotePage({ stage, rounds }: Readonly<ManualVotePageProps>) {

    const { data, setData, post, processing, errors, hasErrors } = useForm<Required<ManualVoteForm>>({
        votes: rounds.map((round) => ({
            round_id: round.id,
            song_ids: {
                first: 0,
                second: 0,
                third: 0
            }
        }))
    });

    const positionHasError = (roundIndex: number, position: string): boolean => {
        return errors[`votes.${roundIndex}.song_ids.${position}`] !== undefined;
    };

    const voteHandler = (index: number, song_id: number, position: string): void => {
        const newData = [...data.votes];
        newData[index].song_ids[position] = song_id;
        setData('votes', newData);
    };

    const isChecked = (index: number, song_id: number, position: string): boolean => {
        const newData = [...data.votes];
        return newData[index].song_ids[position] === song_id;
    };

    const cancelHandler = (): void => {
        router.get(route('admin.stages'));
    };

    const submitHandler = (e): void => {
        e.preventDefault();
        post(route('stages.manual-vote.store', { id: stage.id }));
    };

    return (
        <AppLayout>
            <Head title="Manual Vote"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Manual Voting for "{stage.title}"</h1>
            </div>

            <form className="my-3 mx-4" onSubmit={submitHandler}>
                {hasErrors && (
                    <p className="flex gap-2 text-red-500 mb-3">
                        <CircleAlert/>
                        There was at least one issue with casting the manual vote(s).
                    </p>
                )}
                {rounds.map((round, roundIndex) => (
                    <Card key={round.id} className="p-3 gap-2 mb-3 rounded-0">
                        <CardTitle>{round.title}</CardTitle>
                        <CardContent className="p-0">
                            <div className="flex gap-2">
                                <span className="flex-grow"></span>
                                <span
                                    className={`w-12 text-xs font-bold text-center ${positionHasError(roundIndex, 'first') ? 'text-red-600' : ''}`}>First</span>
                                <span
                                    className={`w-12 text-xs font-bold text-center ${positionHasError(roundIndex, 'second') ? 'text-red-600' : ''}`}>Second</span>
                                <span
                                    className={`w-12 text-xs font-bold text-center ${positionHasError(roundIndex, 'third') ? 'text-red-600' : ''}`}>Third</span>
                            </div>
                            <ul>
                                {round.songs.map((song) => (
                                    <li key={song.id}
                                        className="flex gap-2 items-center select-none hover:bg-indigo-100/50">
                                        <ActImage act={song.act} className="h-10 mr-3"/>
                                        <span className="w-[20em]">{song.act.name}</span>
                                        <span className="mr-auto">{song.title}</span>
                                        {['first', 'second', 'third'].map((position) => (
                                            <label key={`${song.id}-${position}`}
                                                   className={`table-cell w-12 text-center p-2 hover:bg-indigo-100 ${positionHasError(roundIndex, position) ? 'bg-red-100' : ''}`}
                                                   aria-label={`${position} place`}>
                                                <input type="radio" name={`${round.id}-${position}`}
                                                       defaultChecked={isChecked(roundIndex, song.id, position)}
                                                       onClick={() => voteHandler(roundIndex, song.id, position)}/>
                                            </label>
                                        ))}
                                    </li>
                                ))}
                            </ul>
                        </CardContent>
                    </Card>
                ))}

                <div className="flex justify-end">
                    <LoadingButton isLoading={processing}>Cast manual votes</LoadingButton>
                    <Button variant="link" type="button" onClick={cancelHandler}>Cancel</Button>
                </div>
            </form>

        </AppLayout>
    );
}
