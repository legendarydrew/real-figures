import { Bar, BarChart, Tooltip } from 'recharts';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartRoundReferences, ChartTimeXAxis, ChartYAxis } from '@/components/chart-elements';
import { formatDate } from '@/lib/utils';
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
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text">{formatDate(locale, label)}</span>
                    <span className="text-sm flex gap-1 items-center">
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
                        style={{ width: '100%', maxHeight: '300px', aspectRatio: 1.618 }}
                        responsive
                        data={chartData}
                    >
                        <ChartTimeXAxis/>
                        <ChartYAxis label="Votes"/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>

                        <Bar dataKey="count" fill="var(--chart-4-6)"/>
                        <ChartRoundReferences/>
                    </BarChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
