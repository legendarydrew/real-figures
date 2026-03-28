import { Bar, BarChart, CartesianGrid, ReferenceLine, Tooltip } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { cssVar, formatDate } from '@/lib/utils';
import { usePage } from '@inertiajs/react';


interface Props {
    days?: number;
}

export const SubscribersAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const { locale } = usePage().props;
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState<AnalyticsData>();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        if (isLoading) {
            return;
        }
        setIsLoading(true);
        axios.get("/api/analytics/subscribers", { params: { days } })
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
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-2">
                    <span className="display-text text-sm">{formatDate(locale, label)}</span>
                    <span className="flex items-center gap-1 text-xs">
                        {payload[0].value.toLocaleString()} net {payload[0].value === 1 ? 'subscriber' : 'subscribers'}
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <section id="analyticsSubscribers" className="analytics-section">
            <h2 className="analytics-section-title">Email subscribers</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <BarChart
                        style={{ width: '100%', maxHeight: '200px', aspectRatio: 3 }}
                        responsive
                        data={chartData}
                        stackOffset="sign">
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Net subscriptions"/>
                        <ReferenceLine y={0} stroke={cssVar('--secondary')}/>
                        <Bar dataKey="eventValue" fill="var(--chart-2-5)"/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <ChartRoundReferences/>
                    </BarChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
