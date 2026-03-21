import { Bar, BarChart, Tooltip } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';


interface Props {
    days?: number;
}

export const ContactMessagesAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
                        style={{ width: '100%', maxHeight: '200px', aspectRatio: 3 }}
                        responsive
                        data={chartData}
                    >
                        <ChartDateXAxis/>
                        <ChartYAxis label="Messages sent"/>

                        <Bar dataKey="eventValue" fill="var(--chart-3-5)"/>
                        <Tooltip/>
                        <ChartRoundReferences/>
                    </BarChart>
                ) : (<Nothing>No data available.</Nothing>)}
            </LoadingOverlay>
        </section>
    )
}
