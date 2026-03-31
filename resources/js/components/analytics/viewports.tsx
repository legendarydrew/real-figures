import { Bar, BarChart, CartesianGrid } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { stringToChartColour } from '@/lib/utils';


interface Props {
    days?: number;
}

export const ViewportsAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/viewports", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    return (
        <section id="analyticsViewports" className="analytics-section">
            <h2 className="analytics-section-title">Viewports</h2>

            <LoadingOverlay isLoading={isLoading}>

                {chartData && (
                    <BarChart data={chartData.data} style={{ width: '100%', maxHeight: '300px', aspectRatio: 3 }}
                              responsive>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Views"/>

                        {chartData.keys.map(key => (
                            <Bar
                                key={key}
                                dataKey={key}
                                stackId="sections"
                                fill={stringToChartColour(key)}
                            />
                        ))}
                        <ChartRoundReferences position="start"/>
                    </BarChart>
                )}

                <table className="data-table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col" className="text-left">Viewport size</th>
                        <th scope="col" className="text-right">Views</th>
                    </tr>
                    </thead>
                    <tbody>
                    {chartData ? chartData.table.map((row, index) => (
                        <tr key={index}>
                            <th scope="row">
                                    <span className="block size-4"
                                          style={{ backgroundColor: stringToChartColour(row.viewport) }}></span>
                            </th>
                            <th className="text-left" scope="row">{row.viewport}</th>
                            <td className="text-right">{row.views}</td>
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
