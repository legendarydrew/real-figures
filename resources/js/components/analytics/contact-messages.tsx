import { Bar, BarChart, CartesianGrid, Tooltip } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';


interface Props {
    days?: number;
}

export const ContactMessagesAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/contact", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    return (
        <section id="analyticsContactMessages" className="analytics-section">
            <h2 className="analytics-section-title">Contact messages sent</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData ? (
                    <BarChart
                        style={{ width: '100%', height: '200px', aspectRatio: 3 }}
                        responsive
                        data={chartData}
                    >
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Messages sent"/>

                        <Bar dataKey="eventCount" fill="var(--chart-3-5)"/>
                        <Tooltip/>
                        <ChartRoundReferences/>
                    </BarChart>
                ) : (<Nothing>No data available.</Nothing>)}
            </LoadingOverlay>
        </section>
    )
}
