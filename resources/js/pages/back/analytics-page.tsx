import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import { SongPlaysAnalytics } from '@/components/analytics/song-plays';

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
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

                <Heading title="Analytics"/>

                {/* Tabs? */}

                {/*<CollapseOpenAnalytics/>*/}
                {/*<VotesAnalytics/>*/}
                {/*<ReferrersAnalytics />*/}
                <SongPlaysAnalytics/>
            </div>
        </AppLayout>
    );
}
