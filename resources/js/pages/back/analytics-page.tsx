import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { AdminHeader } from '@/components/admin/admin-header';
import { ContactMessagesAnalytics } from '@/components/analytics/contact-messages';
import { Button } from '@/components/ui/button';
import React, { useState } from 'react';
import { DonationsDailyAnalytics } from '@/components/analytics/donations-daily';
import { DonationsMadeAnalytics } from '@/components/analytics/donations-made';
import { DonationsAnonymousAnalytics } from '@/components/analytics/donations-anonymous';
import { DonationsTotalAnalytics } from '@/components/analytics/donations-total';
import { PagesViewedAnalytics } from '@/components/analytics/pages-viewed';
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
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import { VoteChoicesAnalytics } from '@/components/analytics/vote-choices';
import { PlaylistAnalytics } from '@/components/analytics/playlist';

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
    const [dayCount, setDayCount] = useState<number>(7);

    const dayOptions = [3, 7, 14, 30, 60, 90];

    const sectionHandler = (section: string) => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setSection(AnalyticsSection[section]);
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Analytics"/>
            <div className="admin-content">

                <AdminHeader title="Analytics"/>

                <div className="flex flex-col md:flex-row gap-8">

                    <div className="md:w-1/6">
                        <div className="sticky top-4 flex flex-col gap-2">

                            <Select onValueChange={setDayCount}>
                                <SelectTrigger className="rounded-xs">Last {dayCount} days</SelectTrigger>
                                <SelectContent>
                                    {dayOptions.map((days) => (
                                        <SelectItem key={days} value={days}>Last {days} days</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>

                            <menu className="flex flex-col gap-1 items-stretch">
                                {Object.keys(AnalyticsSection).map((key) => (
                                    <Button key={key} type="button" size="sm"
                                            variant={section === AnalyticsSection[key] ? 'primary' : 'default'}
                                            className="justify-start text-sm"
                                            onClick={() => sectionHandler(key)}>{AnalyticsSection[key]}</Button>
                                ))}
                            </menu>
                        </div>
                    </div>

                    <div className="md:w-5/6 flex flex-col gap-8">
                        {section === AnalyticsSection.DONATIONS && (
                            <>
                                <DonationsTotalAnalytics days={dayCount}/>
                                <DonationsDailyAnalytics days={dayCount}/>
                                <DonationsMadeAnalytics days={dayCount}/>
                                <DonationsAnonymousAnalytics days={dayCount}/>
                            </>
                        )}
                        {section === AnalyticsSection.VISITORS && (
                            <>
                                <PageViewsAnalytics days={dayCount}/>
                                <PagesViewedAnalytics days={dayCount}/>
                                <NewVsReturningAnalytics days={dayCount}/>
                                <BrowsersAnalytics days={dayCount}/>
                                <OperatingSystemsAnalytics days={dayCount}/>
                                <PlatformAnalytics days={dayCount}/>
                                <ViewportsAnalytics days={dayCount}/>
                                <ReferrersAnalytics days={dayCount}/>
                                <CountriesAnalytics days={dayCount}/>
                            </>
                        )}
                        {section === AnalyticsSection.ACTIVITY && (
                            <>
                                <CollapseOpenAnalytics days={dayCount}/>
                                <OutboundAnalytics days={dayCount}/>
                                <ActViewsAnalytics days={dayCount}/>
                                <SubscribersAnalytics days={dayCount}/>
                                <ContactMessagesAnalytics days={dayCount}/>
                            </>
                        )}
                        {section === AnalyticsSection.CONTEST && (
                            <>
                                <VotesAnalytics days={dayCount}/>
                                <VoteChoicesAnalytics days={dayCount}/>
                                <PlaysAnalytics days={dayCount}/>
                                <SongsPlayedAnalytics days={dayCount}/>
                                <PlaylistAnalytics days={dayCount}/>
                                <GoldenBuzzersMadeAnalytics days={dayCount}/>
                            </>
                        )}
                    </div>
                </div>

            </div>
        </AppLayout>
    );
}
