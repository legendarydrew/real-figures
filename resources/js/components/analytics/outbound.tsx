import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Bar, BarChart } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { Nothing } from '@/components/mode/nothing';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { stringToChartColour } from '@/lib/utils';

interface Props {
    days?: number;
}

export const OutboundAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        if (isLoading) {
            return;
        }
        setIsLoading(true);
        axios.get("/api/analytics/outbound", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    return (
        <section id="analyticsOutbound" className="analytics-section">
            <h2 className="analytics-section-title">Outbound links</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData?.data.length ? (
                    <div className="flex flex-col gap-4">

                        <BarChart aspect={4}
                                  style={{ width: '100%', maxHeight: '200px', aspectRatio: 3 }}
                                  responsive
                                  data={chartData.data}
                        >
                            <ChartDateXAxis/>
                            <ChartYAxis label="Clicks" />

                            {chartData.keys.map(key => (
                                <Bar
                                    key={key}
                                    dataKey={key}
                                    stackId="sections"
                                    fill={stringToChartColour(key ?? 'Other')}
                                />
                            ))}
                            <ChartRoundReferences/>
                        </BarChart>

                        <table className="data-table lg:col-span-2">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col" className="text-left">URL</th>
                                <th scope="col" className="text-right">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            {chartData.table?.length ? chartData.table.map((row, index) => (
                                <tr key={index}>
                                    <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: stringToChartColour(row.url ?? 'Other') }}></span>
                                    </th>
                                    <th className="text-left" scope="row">{row.url}</th>
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
                ) : (<Nothing>No data recorded.</Nothing>)}
            </LoadingOverlay>
        </section>
    )
}
