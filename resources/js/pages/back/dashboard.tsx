import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DashboardSongTotalPlays } from '@/components/admin/dashboard-song-total-plays';
import { DashboardVotesCast } from '@/components/admin/dashboard-votes-cast';
import { DashboardMessageCount } from '@/components/admin/dashboard-message-count';
import Heading from '@/components/heading';
import { DashboardSubscriberCount } from '@/components/admin/dashboard-subscriber-count';
import { DashboardVoteCount } from '@/components/admin/dashboard-vote-count';
import { DashboardDonationCount } from '@/components/admin/dashboard-donation-count';
import { DashboardGoldenBuzzerCount } from '@/components/admin/dashboard-golden-buzzer-count';
import { DashboardAnalyticsViews } from '@/components/admin/dashboard-analytics-views';
import { DashboardContestStatus } from '@/components/admin/dashboard-contest-status';
import { DashboardDate } from '@/components/admin/dashboard-date';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard'
    }
];

export default function Dashboard({
                                      analytics_countries,
                                      analytics_pages,
                                      analytics_views,
                                      contest_status,
                                      donations,
                                      buzzer_count,
                                      message_count,
                                      subscriber_count,
                                      song_plays,
                                      votes,
                                      vote_count
                                  }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard"/>
            <div className="admin-content">

                <Heading title="Dashboard"/>

                <div className="grid auto-rows-max gap-3 md:grid-cols-2 lg:grid-cols-5">
                    <DashboardDate className="lg:col-span-2"/>

                    <DashboardContestStatus data={contest_status}
                                            className="lg:col-start-3 lg:col-span-3 lg:row-span-3"/>
                    <DashboardMessageCount className="lg:col-start-1"
                                           message_count={message_count}/>
                    <DashboardSubscriberCount className="lg:col-start-2"
                                              subscriber_count={subscriber_count}/>
                    <DashboardVoteCount className="lg:col-start-3 lg:col-span-3"
                                        vote_count={vote_count}/>
                    <DashboardDonationCount className="lg:col-start-1 lg:col-span-1"
                                            donation_count={donations.count}/>
                    <DashboardGoldenBuzzerCount className="lg:col-start-2"
                                                buzzer_count={buzzer_count}/>

                </div>

                <DashboardAnalyticsViews className="p-3" data={analytics_views}/>

                <DashboardSongTotalPlays className="p-3" data={song_plays}/>

                <DashboardVotesCast className="p-3" data={votes}/>

            </div>
        </AppLayout>
    );
}
