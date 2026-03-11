import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { ReferrersAnalytics } from '@/components/analytics/referrers';

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

                <Heading title="Analytics"/>

                {/* Tabs? */}

                {/*<CollapseOpenAnalytics/>*/}
                {/*<VotesAnalytics/>*/}
                <ReferrersAnalytics />
            </div>
        </AppLayout>
    );
}
