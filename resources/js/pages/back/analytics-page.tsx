import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { AdminHeader } from '@/components/admin/admin-header';
import { ViewportsAnalytics } from '@/components/analytics/viewports';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Analytics',
        href: '/analytics'
    }
];

export default function AnalyticsPage() {

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Analytics"/>
            <div className="admin-content">

                <AdminHeader title="Analytics"/>

            {/*    /!* Tabs? *!/*/}

            {/*    /!*<CollapseOpenAnalytics/>*!/*/}
            {/*    /!*<VotesAnalytics/>*!/*/}
            {/*    /!*<ReferrersAnalytics />*!/*/}
            {/*    /!*<SongPlaysAnalytics/>*!/*/}
            {/*    /!*<PlaysAnalytics/>*!/*/}
            {/*    /!*<DonationsMadeAnalytics/>*!/*/}
            {/*    /!*<PageViewsAnalytics/>*!/*/}
            {/*    /!*<PagesAnalytics/>*!/*/}
            {/*    /!*<DonationsAnonymousAnalytics/>*!/*/}
            {/*    /!*<ActViewsAnalytics/>*!/*/}
            {/*    /!*<DonationsDailyAnalytics />*!/*/}
            {/*    /!*<SubscribersAnalytics/>*!/*/}
            {/*    /!*<DonationsTotalAnalytics/>*!/*/}
            {/*    /!*<OutboundAnalytics/>*!/*/}
            {/*    /!*<CountriesAnalytics/>*!/*/}
            {/*    /!*<GoldenBuzzersMadeAnalytics/>*!/*/}
            {/*    <BrowsersAnalytics />*/}
            {/*    <OperatingSystemsAnalytics/>*/}
            {/*    <NewVsReturningAnalytics/>*/}
            {/*    <PlatformAnalytics/>*/}
                <ViewportsAnalytics/>
            </div>
        </AppLayout>
    );
}
