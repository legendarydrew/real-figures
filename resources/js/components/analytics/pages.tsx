import HeadingSmall from '@/components/heading-small';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Pie, PieChart, PieSectorShapeProps, Sector } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';

interface Props {
    days?: number;
}

export const PagesAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        axios.get("/api/analytics/pages", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const stringToColor = (str: string) => {
        if (str === 'Other') {
            return 'var(--chart-1-5)';
        }

        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash % 360);
        return `hsl(${hue}, 65%, 55%)`;
    }

    const pieSector = (props: PieSectorShapeProps) => {
        return <Sector {...props} fill={stringToColor(chartData.grouped[props.index].url)}/>;
    };

    return (
        <section id="analyticsPages" className="analytics-section">
            <HeadingSmall title="Pages viewed"/>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <div className="grid lg:grid-cols-3 gap-8">

                        <PieChart style={{ width: '100%', aspectRatio: 1 }} responsive>
                            <Pie
                                dataKey="count"
                                data={chartData.grouped}
                                fill="#8884d8"
                                shape={pieSector}
                                label
                            />
                        </PieChart>

                        <table className="data-table lg:col-span-2">
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
                                      style={{ backgroundColor: stringToColor(row.url) }}></span>
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
                )}
            </LoadingOverlay>
        </section>
    )
}
