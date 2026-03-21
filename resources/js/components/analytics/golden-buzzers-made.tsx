import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { Line, LineChart, Tooltip } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';


interface Props {
    days?: number
}

export const GoldenBuzzersMadeAnalytics: React.FC<Props> = ({ days = 7 }) => {

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
        return axios.get("/api/analytics/golden-buzzers/made", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });

    }

    return (
        <section id="analyticsGoldenBuzzersMade" className="analytics-section">
            <h2 className="analytics-section-title">Golden Buzzers made</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <LineChart
                        style={{ width: '100%', maxHeight: '200px', aspectRatio: 1.618 }}
                        responsive
                        data={chartData}
                    >
                        <ChartDateXAxis/>
                        <ChartYAxis label="Count"/>
                        <Tooltip/>

                        <Line dataKey="started" stroke="var(--gold-light)"/>
                        <Line dataKey="completed" stroke="var(--gold)" strokeWidth={2}/>
                        <ChartRoundReferences/>
                    </LineChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
