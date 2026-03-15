import { Bar, BarChart, XAxis, YAxis } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';


interface Props {
    days?: number;
}

export const SongPlaysAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState<AnalyticsData>();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        axios.get("/api/analytics/songs", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    function stringToColor(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash % 360);
        return `hsl(${hue}, 65%, 55%)`;
    }

    return (
        <section id="analyticsSongPlays" className="analytics-section">
            <HeadingSmall title="Songs played"/>

            <LoadingOverlay isLoading={isLoading}>
                {chartData && (
                    <BarChart
                        style={{ width: '100%', maxHeight: '300px', aspectRatio: 1.618 }}
                        responsive
                        data={chartData.data}
                    >
                        <XAxis dataKey="date"/>
                        <YAxis/>

                        {chartData.keys.map(key => (
                            <Bar
                                key={key}
                                dataKey={key}
                                stackId="sections"
                                fill={stringToColor(key)}
                            />
                        ))}
                    </BarChart>
                )}
                <table className="data-table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col" className="text-left">Act</th>
                        <th scope="col" className="text-right">Count</th>
                    </tr>
                    </thead>
                    <tbody>
                    {chartData?.table?.length ? chartData.table.map((row, index) => (
                        <tr key={index}>
                            <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: stringToColor(row.slug) }}></span>
                            </th>
                            <th className="text-left" scope="row">{row.act.name}</th>
                            <td className="text-right">{row.count}</td>
                        </tr>
                    )) : (
                        <tr>
                            <td colSpan="4" className="nothing">No data recorded.</td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </LoadingOverlay>
        </section>
    )
}
