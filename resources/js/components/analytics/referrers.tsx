import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { Pie, PieChart, PieSectorShapeProps, Sector } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { stringToChartColour } from '@/lib/utils';

interface Props {
    days?: number;
}

export const ReferrersAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/referrers", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const pieSector = (props: PieSectorShapeProps) => {
        return <Sector {...props} fill={stringToChartColour(chartData[props.index].referrer)}/>;
    };

    return (
        <section id="analyticsReferrers" className="analytics-section">
            <h2 className="analytics-section-title">Referrers</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="grid lg:grid-cols-3 gap-8">

                        <PieChart style={{ width: '100%', aspectRatio: 1 }} responsive>
                            <Pie
                                dataKey="count"
                                data={chartData}
                                fill="#8884d8"
                                shape={pieSector}
                            />
                        </PieChart>

                        <div className="analytics-section-scroll lg:col-span-2">
                            <table className="data-table">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col" className="text-left">Referrer</th>
                                    <th scope="col" className="text-right">Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                {chartData?.length ? chartData.map((row, index) => (
                                    <tr key={index}>
                                        <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: stringToChartColour(row.referrer) }}></span>
                                        </th>
                                        <th className="text-left" scope="row">{row.referrer.length ? row.referrer : (
                                            <em className="text-muted-foreground font-normal">none</em>)}</th>
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
