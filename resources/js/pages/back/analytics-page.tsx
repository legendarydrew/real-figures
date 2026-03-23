import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { AdminHeader } from '@/components/admin/admin-header';
import { ContactMessagesAnalytics } from '@/components/analytics/contact-messages';
import { Button } from '@/components/ui/button';
import { useState } from 'react';
import { DonationsDailyAnalytics } from '@/components/analytics/donations-daily';
import { DonationsMadeAnalytics } from '@/components/analytics/donations-made';
import { DonationsAnonymousAnalytics } from '@/components/analytics/donations-anonymous';
import { DonationsTotalAnalytics } from '@/components/analytics/donations-total';
import { PagesAnalytics } from '@/components/analytics/pages';
import { PageViewsAnalytics } from '@/components/analytics/page-views';
import { ViewportsAnalytics } from '@/components/analytics/viewports';
import { NewVsReturningAnalytics } from '@/components/analytics/new-vs-returning';
import { BrowsersAnalytics } from '@/components/analytics/browsers';
import { OperatingSystemsAnalytics } from '@/components/analytics/os';
import { PlatformAnalytics } from '@/components/analytics/platform';
import { ReferrersAnalytics } from '@/components/analytics/referrers';
import { CountriesAnalytics } from '@/components/analytics/countries';
import { VotesAnalytics } from '@/components/analytics/votes';
import { SongsPlayedAnalytics } from '@/components/analytics/songs-played';
import { GoldenBuzzersMadeAnalytics } from '@/components/analytics/golden-buzzers-made';
import { PlaysAnalytics } from '@/components/analytics/plays';
import { CollapseOpenAnalytics } from '@/components/analytics/collapse-open';
import { ActViewsAnalytics } from '@/components/analytics/act-views';
import { OutboundAnalytics } from '@/components/analytics/outbound';
import { SubscribersAnalytics } from '@/components/analytics/subscribers';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Analytics',
        href: '/analytics'
    }
];

enum AnalyticsSection {
    VISITORS = 'Visitors',
    ACTIVITY = 'Activity',
    CONTEST = 'Contest',
    DONATIONS = 'Donations',
}

export default function AnalyticsPage() {

    const [section, setSection] = useState<AnalyticsSection>(AnalyticsSection.VISITORS);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Analytics"/>
            <div className="admin-content">

                <AdminHeader title="Analytics"/>

                <div className="flex flex-col md:flex-row gap-8">

                    <menu className="md:w-1/6 flex flex-col gap-1 items-stretch sticky top-8">
                        {Object.keys(AnalyticsSection).map((key) => (
                            <Button key={key} type="button" size="sm"
                                    variant={section === AnalyticsSection[key] ? 'primary' : 'default'}
                                    className="justify-start text-sm"
                                    onClick={() => setSection(AnalyticsSection[key])}>{AnalyticsSection[key]}</Button>
                        ))}
                    </menu>

                    <div className="md:w-5/6 flex flex-col gap-8">
                        {section === AnalyticsSection.DONATIONS && (
                            <>
                                <DonationsTotalAnalytics/>
                                <DonationsDailyAnalytics/>
                                <DonationsMadeAnalytics/>
                                <DonationsAnonymousAnalytics/>
                            </>
                        )}
                        {section === AnalyticsSection.VISITORS && (
                            <>
                                <PageViewsAnalytics/>
                                <PagesAnalytics/>
                                <NewVsReturningAnalytics/>
                                <BrowsersAnalytics/>
                                <OperatingSystemsAnalytics/>
                                <PlatformAnalytics/>
                                <ViewportsAnalytics/>
                                <ReferrersAnalytics/>
                                <CountriesAnalytics/>
                            </>
                        )}
                        {section === AnalyticsSection.ACTIVITY && (
                            <>
                                <CollapseOpenAnalytics/>
                                <OutboundAnalytics/>
                                <ActViewsAnalytics/>
                                <SubscribersAnalytics/>
                                <ContactMessagesAnalytics/>
                            </>
                        )}
                        {section === AnalyticsSection.CONTEST && (
                            <>
                                <VotesAnalytics/>
                                <PlaysAnalytics/>
                                <SongsPlayedAnalytics/>
                                <GoldenBuzzersMadeAnalytics/>
                            </>
                        )}
                    </div>
                </div>

            </div>
        </AppLayout>
    );
}
