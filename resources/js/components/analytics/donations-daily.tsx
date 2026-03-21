import { Bar, BarChart, Tooltip } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartYAxis } from '@/components/chart-elements';


interface Props {
    days?: number;
}

export const DonationsDailyAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/donations/daily", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    return (
        <section id="analyticsDonationsDaily" className="analytics-section">
            <h2 className="analytics-section-title">Donations by day</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData ? (
                    <BarChart
                        style={{ width: '100%', maxHeight: '200px', aspectRatio: 3 }}
                        responsive
                        data={chartData}
                    >
                        <ChartDateXAxis/>

                        <ChartYAxis label="Amount"/>

                        <Bar dataKey="eventValue" fill="var(--chart-3-5)"/>
                        <Tooltip/>
                    </BarChart>
                ) : (<Nothing>No data available.</Nothing>)}
            </LoadingOverlay>
        </section>
    )
}
