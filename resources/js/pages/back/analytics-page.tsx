import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { AdminHeader } from '@/components/admin/admin-header';
import { CollapseOpenAnalytics } from '@/components/analytics/collapse-open';

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

                {/* Tabs? */}

                <CollapseOpenAnalytics/>
                {/*<VotesAnalytics/>*/}
                {/*<ReferrersAnalytics />*/}
                {/*<SongPlaysAnalytics/>*/}
                {/*<PlaysAnalytics/>*/}
                {/*<DonationsMadeAnalytics/>*/}
                {/*<PageViewsAnalytics/>*/}
                {/*<PagessAnalytics/>*/}
                {/*<DonationsAnonymousAnalytics/>*/}
            </div>
        </AppLayout>
    );
}
