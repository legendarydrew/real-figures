import { LoaderCircleIcon } from 'lucide-react';
import { Bar, BarChart, XAxis, YAxis } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';


interface Props {
    days?: number;
}

export const DonationsAnonymousAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState<AnalyticsData>();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        axios.get("/api/analytics/donations/anonymous", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const fixedColors = {
        'Anonymous': 'var(--chart-1-1)',
        'Not anonymous': 'var(--chart-2-3)'
    };

    function getColor(key) {
        return fixedColors[key] ?? stringToColor(key);
    }

    function stringToColor(str) {
        let hash = 0;
        for (let i = 0; i < (str?.length ?? 0); i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash % 360);
        return `hsl(${hue}, 65%, 55%)`;
    }

    return (
        <section id="analyticsCollapseOpens" className="analytics-section">
            <HeadingSmall title="Donation Anonymity"/>

            {/* TODO an overlay.*/}
            {isLoading && <LoaderCircleIcon/>}

            <div className="flex flex-col lg:flex-row gap-8">
                <div className="lg:w-2/3">
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
                                    stackId="statuses"
                                    fill={getColor(key)}
                                />
                            ))}
                        </BarChart>
                    )}
                </div>
                <div className="lg:w-1/3">
                    <table className="data-table">
                        <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" className="text-left">Status</th>
                            <th scope="col" className="text-right">Count</th>
                        </tr>
                        </thead>
                        <tbody>
                        {chartData ? chartData.table.map((row) => (
                            <tr key={row.name}>
                                <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: getColor(row.section) }}></span>
                                </th>
                                <th className="text-left" scope="row">{row.name}</th>
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
        </section>
    )
}
