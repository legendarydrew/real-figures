import { Bar, BarChart, CartesianGrid } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartRoundReferences, ChartTimeXAxis, ChartYAxis } from '@/components/chart-elements';
import { stringToChartColour } from '@/lib/utils';


interface Props {
    days?: number;
}

export const PlaylistAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/playlist", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((error) => RTToast.error(error.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    return (
        <section id="analyticsPlaylist" className="analytics-section">
            <h2 className="analytics-section-title">Song player playlist</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="flex flex-col lg:flex-row gap-8">
                        <div className="lg:w-2/3">
                            <BarChart
                                style={{ width: '100%', maxHeight: '320px', aspectRatio: 2 }}
                                responsive
                                data={chartData.data}
                            >
                                <CartesianGrid strokeDasharray="3 3"/>
                                <ChartTimeXAxis/>
                                <ChartYAxis label="Uses"/>

                                {chartData.keys.map(key => (
                                    <Bar
                                        key={key}
                                        dataKey={key}
                                        stackId="buttons"
                                        fill={stringToChartColour(key)}
                                    />
                                ))}
                                <ChartRoundReferences position="start"/>
                            </BarChart>
                        </div>
                        <div className="lg:w-1/3">
                            <div className="analytics-section-scroll">
                                <table className="data-table">
                                    <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col" className="text-left">Button</th>
                                        <th scope="col" className="text-right">Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {chartData.table?.length ? chartData.table.map((row, index) => (
                                        <tr key={index}>
                                            <th scope="row">
                                    <span className="block size-4"
                                          style={{ backgroundColor: stringToChartColour(row.button ?? '(not set)') }}></span>
                                            </th>
                                            <th className="text-left" scope="row">
                                                {row.button ?? <em className="text-muted-foreground">none</em>}
                                            </th>
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
                )}
            </LoadingOverlay>
        </section>
    )
}
