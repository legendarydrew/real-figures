import { Bar, BarChart } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ActImage } from '@/components/mode/act-image';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { stringToChartColour } from '@/lib/utils';


interface Props {
    days?: number;
}

export const ActViewsAnalytics: React.FC<Props> = ({ days = 7 }) => {
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

        axios.get("/api/analytics/acts", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    return (
        <section id="analyticsActViews" className="analytics-section">
            <h2 className="analytics-section-title">Act profiles viewed</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="flex flex-col lg:flex-row gap-8">
                        <div className="lg:w-2/3">
                            <BarChart
                                style={{ width: '100%', maxHeight: '320px', aspectRatio: 2 }}
                                responsive
                                data={chartData.data}
                            >
                                <ChartDateXAxis/>
                                <ChartYAxis label="Profile views"/>

                                {chartData.keys.map(key => (
                                    <Bar
                                        key={key}
                                        dataKey={key}
                                        stackId="acts"
                                        fill={stringToChartColour(key)}
                                    />
                                ))}
                                <ChartRoundReferences/>
                            </BarChart>
                        </div>
                        <div className="lg:w-1/3">
                            <table className="data-table">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col" colSpan={2} className="text-left">Act</th>
                                    <th scope="col" className="text-right">Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                {chartData.table?.length ? chartData.table.map((row, index) => (
                                    <tr key={index}>
                                        <th scope="row">
                                    <span className="block size-4"
                                          style={{ backgroundColor: stringToChartColour(row.act?.slug ?? 'Other') }}></span>
                                        </th>
                                        {row.act ? (
                                            <>
                                                <th className="text-left" scope="row">
                                                    {row.act && (<ActImage act={row.act} size={8}/>)}
                                                </th>
                                                <th className="text-left display-text" scope="row">
                                                    {row.act.name}
                                                    {row.act.subtitle && (<small
                                                        className="ml-1 text-muted-foreground">{row.act.subtitle}</small>)}
                                                </th>
                                            </>
                                        ) : (
                                            <th className="text-left display-text" colSpan={2} scope="row">Other</th>
                                        )}
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
                )}
            </LoadingOverlay>
        </section>
    )
}
