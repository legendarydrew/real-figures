import { Bar, BarChart, Tooltip } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartYAxis } from '@/components/chart-elements';


interface Props {
    days?: number
}

export const PlaysAnalytics: React.FC<Props> = ({ days = 7 }) => {

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
        return axios.get("/api/analytics/plays", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });

    }

    return (
        <section id="analyticsPlays" className="analytics-section">
            <HeadingSmall title="Song Plays"/>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <BarChart
                        style={{ width: '100%', maxHeight: '240px', aspectRatio: 3 }}
                        responsive
                        data={chartData}
                    >
                        <ChartDateXAxis dataKey="time"/>
                        <ChartYAxis label="Play count"/>
                        <Tooltip/>

                        <Bar dataKey="count" fill="var(--chart-2-6)"/>
                    </BarChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
