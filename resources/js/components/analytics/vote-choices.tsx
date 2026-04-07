import { Bar, BarChart, CartesianGrid, Tooltip } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { formatDate } from '@/lib/utils';
import { usePage } from '@inertiajs/react';


interface Props {
    days?: number;
}

export const VoteChoicesAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const {locale} = usePage().props;
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
        axios.get("/api/analytics/votes/choices", { params: { days } })
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
                    <span className="display-text">{formatDate(locale, label)}</span>
                    { payload.map((row) => row.value ? (
                        <span key={row.name} className="text-xs flex gap-1 items-center">
                            <b>{row.value.toLocaleString()}&times;</b> {row.name.slice(1)} {row.name === 'x1' ? 'Song' : 'Songs'} chosen
                        </span>
                    ) : '')}
                </div>
            );
        }

        return null;
    };

    const optionColour = (option): string => {
        switch (option) {
            case 'x1':
                return 'var(--chart-3-2)';
            case 'x2':
                return 'var(--chart-3-4)';
            case 'x3':
                return 'var(--chart-3-6)';
            default:
                return 'var(--chart-1-1)';
        }
    }

    return (
        <section id="analyticsVotesChoices" className="analytics-section">
            <h2 className="analytics-section-title">Songs per vote</h2>

            <LoadingOverlay isLoading={isLoading}>

                {chartData && (
                    <BarChart data={chartData.data} style={{ width: '100%', maxHeight: '300px', aspectRatio: 3 }}
                              responsive>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis dataKey="count" label="Votes"/>

                        {chartData.keys.map(key => (
                            <Bar
                                key={key}
                                dataKey={key}
                                stackId="sections"
                                fill={optionColour(key)}
                            />
                        ))}
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <ChartRoundReferences position="start"/>
                    </BarChart>
                )}

                <table className="data-table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col" className="text-left">Number of Songs chosen</th>
                        <th scope="col" className="text-right">Votes</th>
                    </tr>
                    </thead>
                    <tbody>
                    {chartData ? chartData.table.map((row, index) => (
                        <tr key={row.choices}>
                            <th scope="row">
                                    <span className="block size-4"
                                          style={{ backgroundColor: optionColour(row.choices) }}></span>
                            </th>
                            <th className="text-left"
                                scope="row">{row.choices.slice(1) ?? (<em className="text-muted-foreground">not set</em>)}</th>
                            <td className="text-right">{row.count}</td>
                        </tr>
                    )) : (
                        <tr>
                            <td colSpan="3" className="nothing">No data recorded.</td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </LoadingOverlay>
        </section>
    )
}
