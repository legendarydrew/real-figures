import { Bar, BarChart, ReferenceLine, Tooltip } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartYAxis } from '@/components/chart-elements';
import { cssVar } from '@/lib/utils';


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
            <h2 className="analytics-section-title">Email subscribers</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <BarChart
                        style={{ width: '100%', maxHeight: '200px', aspectRatio: 3 }}
                        responsive
                        data={chartData}
                        stackOffset="sign">
                        <ChartDateXAxis/>
                        <ChartYAxis label="Net subscriptions"/>
                        <ReferenceLine y={0} stroke={cssVar('--secondary')}/>
                        <Bar dataKey="eventValue" fill="var(--chart-2-5)"/>
                        <Tooltip/>
                    </BarChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
