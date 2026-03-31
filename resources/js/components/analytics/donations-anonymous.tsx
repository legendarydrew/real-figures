import { Bar, BarChart, CartesianGrid, Tooltip } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { formatDate, stringToChartColour } from '@/lib/utils';
import { usePage } from '@inertiajs/react';


interface Props {
    days?: number;
}

export const DonationsAnonymousAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/donations/anonymous", { params: { days } })
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
                    {payload.map((row) => (
                        <span key={row.name} className="text-sm flex gap-1 items-center">
                            <span className="size-3 inline-block" style={{ backgroundColor: getColor(row.name) }}></span>
                            {row.value ? row.value.toLocaleString() : 'No'} {row.value === 1 ? 'donation' : 'donations'}
                        </span>
                    ))}
                </div>
            );
        }

        return null;
    };

    const fixedColors = {
        '1': 'var(--chart-1-1)',
        'Anonymous': 'var(--chart-1-1)',
        '0': 'var(--chart-2-3)',
        'Not anonymous': 'var(--chart-2-3)'
    };

    function getColor(key) {
        return fixedColors[key] ?? stringToChartColour(key);
    }

    return (
        <section id="analyticsCollapseOpens" className="analytics-section">
            <h2 className="analytics-section-title">Donation Anonymity</h2>

            <LoadingOverlay isLoading={isLoading}>
                <div className="flex flex-col lg:flex-row gap-8">
                    <div className="lg:w-2/3">
                        {chartData && (
                            <BarChart
                                style={{ width: '100%', maxHeight: '200px', aspectRatio: 3 }}
                                responsive
                                data={chartData.data}
                            >
                                <CartesianGrid strokeDasharray="3 3"/>
                                <ChartDateXAxis/>
                                <ChartYAxis label="Donations"/>

                                {chartData.keys.map(key => (
                                    <Bar
                                        key={key}
                                        dataKey={key}
                                        stackId="statuses"
                                        fill={getColor(key)}
                                    />
                                ))}
                                <Tooltip content={tooltipContent} isAnimationActive={false}/>
                                <ChartRoundReferences position="start"/>
                            </BarChart>
                        )}
                    </div>
                    <div className="lg:w-1/3">
                        <div className="analytics-section-scroll">
                            <table className="data-table">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col" className="text-left">Status</th>
                                    <th scope="col" className="text-right">Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                {chartData ? chartData.table.map((row) => (
                                    <tr key={row.name}>
                                        <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: getColor(row.name) }}></span>
                                        </th>
                                        <th className="text-left" scope="row">{row.name}</th>
                                        <td className="text-right">{row.count}</td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan="3" className="nothing">No data recorded.</td>
                                    </tr>
                                )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </LoadingOverlay>
        </section>
    )
}
