import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { CartesianGrid, Line, LineChart, Tooltip } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { formatDate } from '@/lib/utils';
import { usePage } from '@inertiajs/react';


interface Props {
    days?: number
}

export const DonationsMadeAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const {locale} = usePage().props;
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
        return axios.get("/api/analytics/donations/made", { params: { days } })
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
                        <span className="size-3 inline-block bg-(--donation-light)"></span>
                        {payload[1].value.toLocaleString()} started
                    </span>
                    <span className="flex items-center gap-1 text-xs">
                        <span className="size-3 inline-block bg-(--donation)"></span>
                        {payload[0].value.toLocaleString()} completed
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <section id="analyticsDonations" className="analytics-section">
            <h2 className="analytics-section-title">Donations made</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <LineChart
                        style={{ width: '100%', maxHeight: '200px', aspectRatio: 1.618 }}
                        responsive
                        data={chartData}
                    >
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Count"/>

                        <Line dataKey="started" stroke="var(--donation-light)"/>
                        <Line dataKey="completed" stroke="var(--donation)" strokeWidth={2}/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <ChartRoundReferences/>
                    </LineChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
