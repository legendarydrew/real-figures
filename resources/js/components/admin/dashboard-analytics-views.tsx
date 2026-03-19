import { CartesianGrid, Line, LineChart, Tooltip } from 'recharts';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { cssVar, formatDate } from '@/lib/utils';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';


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

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-2">
                    <span className="display-text text-sm">{formatDate(locale as string, label)}</span>
                    <span className="flex items-center gap-1 text-xs">
                        <span className="size-3 inline-block bg-(--chart-4-3)"></span>
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'}&nbsp;
                        {payload[0].value === 1 ? 'page view' : 'page views'}
                    </span>
                    <span className="flex items-center gap-1 text-xs">
                        <span className="size-3 inline-block bg-(--chart-2-3)"></span>
                        {payload[1].value ? payload[1].value.toLocaleString() : 'No'}&nbsp;
                        {payload[1].value === 1 ? 'Visitor' : 'Visitors'}
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <Card className={className}>
            <CardHeader>
                <CardTitle>
                    Page views <small>within the last two weeks</small>
                </CardTitle>
            </CardHeader>
            <CardContent>
                {data.length ? (
                    <LineChart responsive data={data} width="100%" height={200}>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis dataKey="views" yAxisId="pageViews" label="Views"/>
                        <ChartYAxis dataKey="visitors" yAxisId="pageVisitors" label="Visitors" orientation="right"/>

                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <Line dataKey="views" yAxisId="pageViews" label="Page views" dot={false} strokeWidth={2}
                              stroke={cssVar('--chart-4-3')}/>
                        <Line dataKey="visitors" yAxisId="pageVisitors" label="Visitors" dot={false} strokeWidth={2}
                              stroke={cssVar('--chart-2-3')}/>

                        <ChartRoundReferences/>
                    </LineChart>
                ) : (
                    <Nothing>
                        No page views information.
                    </Nothing>
                )}
            </CardContent>
        </Card>
    );
};
