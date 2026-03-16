import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { Bar, BarChart, ResponsiveContainer, XAxis, YAxis } from 'recharts';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { Nothing } from '@/components/mode/nothing';

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

    const stringToColor = (str: string) => {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash % 360);
        return `hsl(${hue}, 65%, 55%)`;
    }

    return (
        <section id="analyticsOutbound" className="analytics-section">
            <h2 className="analytics-section-title">Outbound links</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData?.data.length ? (
                    <div className="flex flex-col gap-4">

                        <ResponsiveContainer className="w-full">
                            <BarChart aspect={4}
                                style={{ width: '100%', maxHeight: '300px' }}
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
                                        fill={getColor(key)}
                                    />
                                ))}
                            </BarChart>
                        </ResponsiveContainer>

                        <table className="data-table lg:col-span-2">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col" className="text-left">URL</th>
                                <th scope="col" className="text-right">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            {chartData.data?.length ? chartData.data.map((row, index) => (
                                <tr key={index}>
                                    <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: stringToColor(row.linkUrl) }}></span>
                                    </th>
                                    <th className="text-left" scope="row">{row.linkUrl}</th>
                                    <td className="text-right">{row.eventCount}</td>
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
