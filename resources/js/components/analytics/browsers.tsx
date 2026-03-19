import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Pie, PieChart, PieSectorShapeProps, Sector } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';

interface Props {
    days?: number;
}

export const BrowsersAnalytics: React.FC<Props> = ({ days = 7 }) => {
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
        axios.get("/api/analytics/browsers", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const stringToColor = (str: string) => {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash % 360);
        return `hsl(${hue}, 65%, 55%)`;
    }

    const pieSector = (props: PieSectorShapeProps) => {
        return <Sector {...props} fill={stringToColor(chartData[props.index].browser)}/>;
    };

    return (
        <section id="analyticsBrowsers" className="analytics-section">
            <h2 className="analytics-section-title">Browsers</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="grid lg:grid-cols-3 gap-8 items-start">

                        <PieChart style={{ width: '100%', aspectRatio: 1 }} responsive>
                            <Pie
                                dataKey="screenPageViews"
                                data={chartData}
                                fill="#8884d8"
                                shape={pieSector}
                            />
                        </PieChart>

                        <table className="data-table lg:col-span-2">
                            <thead>
                            <tr>
                                <th scope="col" colSpan={2} className="text-left">Browser</th>
                                <th scope="col" className="text-right">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            {chartData?.length ? chartData.map((row, index) => (
                                <tr key={index}>
                                    <th scope="row">
                                        <span className="block size-4"
                                              style={{ backgroundColor: stringToColor(row.browser) }}></span>
                                    </th>
                                    <th className="text-left" scope="row">{row.browser}</th>
                                    <td className="text-right">{row.screenPageViews}</td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="3" className="nothing">No data recorded.</td>
                                </tr>
                            )}
                            </tbody>
                        </table>
                    </div>
                )}
            </LoadingOverlay>
        </section>
    )
}
