import AppLayout from '@/layouts/app-layout';
import { Head, router, useForm } from '@inertiajs/react';
import React from 'react';
import { ManualVoteRoundChoice, Round } from '@/types';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { LoadingButton } from '@/components/ui/loading-button';
import { Button } from '@/components/ui/button';
import { CircleAlert } from 'lucide-react';
import { ManualVoteItem } from '@/components/admin/manual-vote-item';

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

    const positionErrors = (roundIndex: number): string[] => {
        return ['first', 'second', 'third'].filter((position) => errors[`votes.${roundIndex}.song_ids.${position}`] !== undefined);
    };

    const voteHandler = (roundIndex: number, votes: ManualVoteRoundChoice): void => {
        const updatedVotes = [...data.votes];
        updatedVotes[roundIndex].song_ids = votes;
        setData('votes', updatedVotes);
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
                            <ManualVoteItem round={round} votes={data.votes[roundIndex].song_ids}
                                            positionErrors={positionErrors(roundIndex)}
                                            onChange={(v) => voteHandler(roundIndex, v)}/>
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
