import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Pie, PieChart, PieSectorShapeProps, Sector } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { stringToChartColour } from '@/lib/utils';

interface Props {
    days?: number;
}

export const PagesViewedAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/pages", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const pieSector = (props: PieSectorShapeProps) => {
        return <Sector {...props} fill={stringToChartColour(chartData.grouped[props.index].url)}/>;
    };

    return (
        <section id="analyticsPages" className="analytics-section">
            <h2 className="analytics-section-title">Pages viewed</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="grid lg:grid-cols-3 gap-8">

                        <PieChart style={{ width: '100%', aspectRatio: 1 }} responsive>
                            <Pie
                                dataKey="count"
                                data={chartData.grouped}
                                fill="#8884d8"
                                shape={pieSector}
                            />
                        </PieChart>

                        <div className="analytics-section-scroll">
                            <table className="data-table">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col" className="text-left">Title / URL</th>
                                    <th scope="col" className="text-right">Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                {chartData?.data?.length ? chartData.data.map((row, index) => (
                                    <tr key={index}>
                                        <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: stringToChartColour(row.url) }}></span>
                                        </th>
                                        <th className="text-left" scope="row">
                                            {row.title}
                                            <a href={row.url} className="block text-xs" target="_blank">{row.url}</a>
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
                )}
            </LoadingOverlay>
        </section>
    )
}
