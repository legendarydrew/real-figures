import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DashboardSongTotalPlays } from '@/components/admin/dashboard-song-total-plays';
import { DashboardSongsPlayed } from '@/components/admin/dashboard-songs-played';
import { DashboardVotesCast } from '@/components/admin/dashboard-votes-cast';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard'
    }
];

export default function Dashboard({ song_plays, votes }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard"/>
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <div className="grid auto-rows-min gap-4 md:grid-cols-2">
                    <div>
                        <DashboardSongTotalPlays data={song_plays}/>
                    </div>
                    <div>
                        <DashboardVotesCast data={votes}/>
                    </div>
                    <div
                        className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern
                            className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20"/>
                    </div>
                    <div>
                        <DashboardSongsPlayed data={song_plays}/>
                    </div>
                </div>
                <div
                    className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <PlaceholderPattern
                        className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20"/>
                </div>
            </div>
        </AppLayout>
    );
}
