import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DashboardSongTotalPlays } from '@/components/admin/dashboard-song-total-plays';
import { DashboardSongsPlayed } from '@/components/admin/dashboard-songs-played';
import { DashboardVotesCast } from '@/components/admin/dashboard-votes-cast';
import { DashboardMessageCount } from '@/components/admin/dashboard-message-count';
import Heading from '@/components/heading';
import { DashboardDonations } from '@/components/admin/dashboard-donations';
import { DashboardSubscriberCount } from '@/components/admin/dashboard-subscriber-count';
import { DashboardVoteCount } from '@/components/admin/dashboard-vote-count';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard'
    }
];

export default function Dashboard({ donations, message_count, subscriber_count, song_plays, votes, vote_count }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard"/>
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <Heading title="Dashboard"/>

                <div className="grid auto-rows-min gap-x-5 gap-y-10 md:grid-cols-2 lg:grid-cols-5">
                    <DashboardMessageCount className="lg:col-start-1 lg:row-start-1 lg:col-span-1 lg:row-span-1" message_count={message_count}/>
                    <DashboardSubscriberCount className="lg:col-start-2 lg:row-start-1 lg:col-span-1 lg:row-span-1" subscriber_count={subscriber_count}/>
                    <DashboardVoteCount className="lg:col-start-3 lg:row-start-1 lg:col-span-3 lg:row-span-1" vote_count={vote_count}/>

                    <DashboardSongTotalPlays className="lg:col-start-1 lg:row-start-2 lg:col-span-2 lg:row-span-2" data={song_plays}/>
                    <DashboardSongsPlayed className="lg:col-start-1 lg:row-start-4 lg:col-span-2 lg:row-span-2" data={song_plays}/>

                    <DashboardVotesCast className="lg:col-start-3 lg:row-start-2 lg:col-span-3 lg:row-span-2" data={votes}/>

                    <DashboardDonations className="lg:col-start-3 lg:row-start-4 lg:col-span-3 lg:row-span-3" data={donations}/>
                </div>
            </div>
        </AppLayout>
    );
}
