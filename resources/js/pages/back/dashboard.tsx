import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DashboardSongTotalPlays } from '@/components/admin/dashboard-song-total-plays';
import { DashboardSongsPlayed } from '@/components/admin/dashboard-songs-played';
import { DashboardVotesCast } from '@/components/admin/dashboard-votes-cast';
import { DashboardMessageCount } from '@/components/admin/dashboard-message-count';
import Heading from '@/components/heading';
import { DashboardDonations } from '@/components/admin/dashboard-donations';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard'
    }
];

export default function Dashboard({ donations, message_count, song_plays, votes }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard"/>
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <Heading title="Dashboard"/>

                <div className="grid auto-rows-min gap-4 lg:grid-cols-2">
                    <div className="col-span-2">
                        <DashboardMessageCount message_count={message_count}/>
                    </div>
                    <div>
                        <DashboardSongTotalPlays data={song_plays}/>
                    </div>
                    <div>
                        <DashboardVotesCast data={votes}/>
                    </div>
                    <div>
                        <DashboardSongsPlayed data={song_plays}/>
                    </div>
                    <DashboardDonations data={donations}/>
                </div>
            </div>
        </AppLayout>
    );
}
