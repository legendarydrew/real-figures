import { CartesianGrid, Line, LineChart, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { cssVar } from '@/lib/utils';

type DashboardAnalyticsViewsData = {
    date: string;
    views: number;
    visitors: number;
}[];

interface DashboardAnalyticsViewsProps {
    data: DashboardAnalyticsViewsData;
    className?: string;
}

export const DashboardAnalyticsViews: React.FC<DashboardAnalyticsViewsProps> = ({ data, className }) => {

    const { locale } = usePage().props;

    const formatDate = (timestamp: string): string => {
        return new Date(timestamp).toLocaleDateString(locale);
    };

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text">{formatDate(label)}</span>
                    <span className="text-sm">
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'page view' : 'page views'}
                    </span>
                    <span className="text-sm">
                        {payload[1].value ? payload[1].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'visitor' : 'visitors'}
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">Page views <small>within the last week</small></CardTitle>
            <CardContent className="px-0">
                {data.length ? (
                    <ResponsiveContainer className="w-full h-[12rem]" aspect={3.75}>
                        <LineChart data={data} margin={2}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <XAxis dataKey="date"
                                   tickFormatter={formatDate}
                                   className="display-text font-normal text-xs"/>
                            <YAxis className="display-text font-normal text-xs"/>
                            <Tooltip content={tooltipContent} isAnimationActive={false}/>
                            <Line dataKey="views" label="Page views" dot={false} strokeWidth={2}
                                  stroke={cssVar('--primary')}/>
                            <Line dataKey="visitors" label="Visitors" dot={false} strokeWidth={2}
                                  stroke={cssVar('--secondary')}/>
                        </LineChart>
                    </ResponsiveContainer>
                ) : (
                    <Nothing className="w-full h-full">
                        No information about page views.
                    </Nothing>
                )}
            </CardContent>
        </Card>
    );
};
