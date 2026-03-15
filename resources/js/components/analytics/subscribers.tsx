import { Bar, BarChart, ReferenceLine, Tooltip, XAxis, YAxis } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';


interface Props {
    days?: number;
}

export const SubscribersAnalytics: React.FC<Props> = ({ days = 7 }) => {
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

    return (
        <section id="analyticsSubscribers" className="analytics-section">
            <HeadingSmall title="Email subscribers"/>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <BarChart
                        style={{ width: '100%', maxHeight: '300px', aspectRatio: 1.618 }}
                        responsive
                        data={chartData}
                        stackOffset="sign">
                        <XAxis dataKey="date"/>
                        <YAxis/>
                        <ReferenceLine y={0} stroke="#000"/>
                        <Bar dataKey="eventValue" fill="var(--chart-2-5)"/>
                        <Tooltip/>
                    </BarChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
