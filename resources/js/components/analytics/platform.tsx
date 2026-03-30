import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Pie, PieChart, PieSectorShapeProps, Sector } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { stringToChartColour } from '@/lib/utils';

interface Props {
    days?: number;
}

export const PlatformAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/platform", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const pieSector = (props: PieSectorShapeProps) => {
        return <Sector {...props} fill={stringToChartColour(chartData[props.index].platform)}/>;
    };

    return (
        <section id="analyticsPlatform" className="analytics-section">
            <h2 className="analytics-section-title">Platforms</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="grid lg:grid-cols-3 gap-8 items-start">

                        <PieChart style={{ width: '100%', aspectRatio: 1 }} responsive>
                            <Pie
                                dataKey="views"
                                data={chartData}
                                fill="#8884d8"
                                shape={pieSector}
                            />
                        </PieChart>

                        <div className="analytics-section-scroll lg:col-span-2">
                            <table className="data-table">
                                <thead>
                                <tr>
                                    <th scope="col" colSpan={2} className="text-left">Platform</th>
                                    <th scope="col" className="text-right">Views</th>
                                </tr>
                                </thead>
                                <tbody>
                                {chartData?.length ? chartData.map((row, index) => (
                                    <tr key={index}>
                                        <th scope="row">
                                        <span className="block size-4"
                                              style={{ backgroundColor: stringToChartColour(row.platform) }}></span>
                                        </th>
                                        <th className="text-left" scope="row">{row.platform}</th>
                                        <td className="text-right">{row.views}</td>
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
