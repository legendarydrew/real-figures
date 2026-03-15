import { Bar, BarChart, Tooltip, XAxis, YAxis } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';


interface Props {
    days?: number
}

export const VotesAnalytics: React.FC<Props> = ({ days = 7 }) => {

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
        return axios.get("/api/analytics/votes", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });

    }

    return (
        <section id="analyticsVotes" className="analytics-section">
            <HeadingSmall title="Votes cast"/>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <BarChart
                        style={{ width: '100%', maxHeight: '300px', aspectRatio: 1.618 }}
                        responsive
                        data={chartData}
                    >
                        <XAxis dataKey="time"/>
                        <YAxis/>
                        <Tooltip/>

                        <Bar dataKey="count" fill="var(--chart-2-6)"/>
                    </BarChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
