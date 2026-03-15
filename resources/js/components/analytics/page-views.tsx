import { CartesianGrid, Line, LineChart, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { LoadingOverlay } from '@/components/mode/loading-overlay';


interface Props {
    days?: number
}

export const PageViewsAnalytics: React.FC<Props> = ({ days = 7 }) => {

    const [chartData, setChartData] = useState<AnalyticsData>();
    const [isLoading, setIsLoading] = useState<boolean>(false);

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        if (isLoading) {
            return;
        }
        setIsLoading(true);
        return axios.get("/api/analytics/page-views", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });

    }

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
                        {payload[1].value ? payload[1].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'Visitor' : 'Visitors'}
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <section id="analyticsVotes" className="analytics-section">
            <HeadingSmall title="Page views"/>

            <LoadingOverlay isLoading={isLoading}>
                {chartData?.length ? (
                    <ResponsiveContainer className="w-full" aspect={5}>
                        <LineChart data={chartData} margin={2}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <XAxis dataKey="date"
                                   tickFormatter={formatDate}
                                   className="display-text font-normal text-xs"/>
                            <YAxis yAxisId="visitorsAxis" className="display-text font-normal text-xs"/>
                            <YAxis yAxisId="viewsAxis" orientation="right"
                                   className="display-text font-normal text-xs"/>
                            <Tooltip content={tooltipContent} isAnimationActive={false}/>
                            <Line dataKey="views" label="Page views" dot={false} strokeWidth={2}
                                  stroke="var(--primary)" yAxisId="viewsAxis"/>
                            <Line dataKey="visitors" label="Visitors" dot={false} strokeWidth={2}
                                  stroke="var(--secondary)" yAxisId="visitorsAxis"/>
                        </LineChart>
                    </ResponsiveContainer>
                ) : (
                    <Nothing>
                        No page views information.
                    </Nothing>
                )}
            </LoadingOverlay>
        </section>
    )
}
