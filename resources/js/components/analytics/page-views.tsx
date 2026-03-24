import { CartesianGrid, Line, LineChart, Tooltip } from 'recharts';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { cssVar, formatDate } from '@/lib/utils';
import { usePage } from '@inertiajs/react';


interface Props {
    days?: number
}

export const PageViewsAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const {locale } = usePage().props;
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

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text">{formatDate(locale, label)}</span>
                    <span className="text-sm flex gap-1 items-center">
                        <span className="size-3 inline-block bg-(--primary)"></span>
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'page view' : 'page views'}
                    </span>
                    <span className="text-sm flex gap-1 items-center">
                        <span className="size-3 inline-block bg-(--secondary)"></span>
                        {payload[1].value ? payload[1].value.toLocaleString() : 'No'} {payload[1].value === 1 ? 'Visitor' : 'Visitors'}
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <section id="analyticsPageViews" className="analytics-section">
            <h2 className="analytics-section-title">Page views</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData?.length ? (
                    <LineChart data={chartData} style={{ width: '100%', height: '240px', aspectRatio: 3 }}
                               responsive
                               margin={2}>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis yAxisId="visitorsAxis" label="Page views" fill={cssVar('--primary')}/>
                        <ChartYAxis yAxisId="viewsAxis" label="Visitors" orientation="right" fill={cssVar('--secondary')}/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <Line dataKey="screenPageViews" label="Page views" dot={false} strokeWidth={2}
                              stroke={cssVar('--primary')} yAxisId="viewsAxis"/>
                        <Line dataKey="activeUsers" label="Visitors" dot={false} strokeWidth={2}
                              stroke={cssVar('--secondary')} yAxisId="visitorsAxis"/>
                        <ChartRoundReferences/>
                    </LineChart>
                ) : (
                    <Nothing>
                        No page views information.
                    </Nothing>
                )}
            </LoadingOverlay>
        </section>
    )
}
