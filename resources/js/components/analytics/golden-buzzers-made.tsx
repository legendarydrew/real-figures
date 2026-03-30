import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { CartesianGrid, Line, LineChart, Tooltip } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { usePage } from '@inertiajs/react';
import { formatDate } from '@/lib/utils';


interface Props {
    days?: number
}

export const GoldenBuzzersMadeAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        return axios.get("/api/analytics/golden-buzzers/made", { params: { days } })
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
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'Golden Buzzer awarded' : 'Golden Buzzers awarded'}
                    </span>
                </div>
            );
        }

        return null;
    };

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
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Count"/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>

                        <Line dataKey="started" stroke="var(--gold-light)"/>
                        <Line dataKey="completed" stroke="var(--gold)" strokeWidth={2}/>
                        <ChartRoundReferences/>
                    </LineChart>
                )}
            </LoadingOverlay>
        </section>
    )
}
