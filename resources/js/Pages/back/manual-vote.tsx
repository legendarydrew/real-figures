import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import React from 'react';
import { Round } from '@/types';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { ActImage } from '@/components/ui/act-image';

interface ManualVotePageProps {
    stageTitle: string;
    rounds: Round[];
}

export default function ManualVotePage({ stageTitle, rounds }: Readonly<ManualVotePageProps>) {

    return (
        <AppLayout>
            <Head title="Manual Vote"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Manual Voting for "{stageTitle}"</h1>
            </div>

            <form className="my-3 mx-4">
                {rounds.map((round) => (
                    <Card key={round.id} className="p-3 mb-3 rounded-0">
                        <CardTitle>{round.title}</CardTitle>
                        <CardContent className="py-2 px-4">
                            <ul>
                                {round.songs.map((song) => {
                                    <li key={song.id}>
                                        <ActImage act={song.act} className="h-10 mr-3"/>
                                        {song.act.name} &ndash; {song.title}
                                    </li>
                                })}
                            </ul>
                        </CardContent>
                    </Card>
                ))}
            </form>

        </AppLayout>
    );
}
