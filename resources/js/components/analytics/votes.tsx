import { Bar, BarChart, CartesianGrid, Tooltip } from 'recharts';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartRoundReferences, ChartTimeXAxis, ChartYAxis } from '@/components/chart-elements';
import { formatDateHour } from '@/lib/utils';
import { usePage } from '@inertiajs/react';


interface Props {
    days?: number
}

export const VotesAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        return axios.get("/api/analytics/votes", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((error) => RTToast.error(error.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text">{formatDateHour(locale, label)}</span>
                    <span className="text-xs flex gap-1 items-center">
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'vote cast' : 'votes cast'}
                    </span>
                </div>
            );
        }

        return null;
    };


    return (
        <section id="analyticsVotes" className="analytics-section">
            <h2 className="analytics-section-title">Votes cast</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <BarChart
                        style={{ width: '100%', height: '200px', aspectRatio: 3 }}
                        responsive
                        data={chartData}
                    >
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartTimeXAxis/>
                        <ChartYAxis label="Votes"/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>

                        <Bar dataKey="count" fill="var(--chart-4-6)"/>
                        <ChartRoundReferences position="start"/>
                    </BarChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
