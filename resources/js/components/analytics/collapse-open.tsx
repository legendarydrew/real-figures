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

export const CollapseOpenAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState<AnalyticsData>();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        axios.get("/api/analytics/collapse", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });
    }

    const fixedColors = {
        // Rules
        'terminology': 'var(--chart-1-1)',
        'contest-brief': 'var(--chart-1-2)',
        'eligibility': 'var(--chart-1-3)',
        'song-criteria': 'var(--chart-1-4)',
        'stage-1-knockout-stage': 'var(--chart-1-5)',
        'stage-2-finals': 'var(--chart-1-6)',
        'how-votes-are-calculated': 'var(--chart-1-7)',
        'the-golden-buzzer': 'var(--chart-1-8)',
        'special-situations': 'var(--chart-1-9)',
        'advice-for-visitors': 'var(--chart-1-10)',

        // About
        'about-catawol-records': 'var(--chart-4-1)',
        'about-the-song': 'var(--chart-4-2)',
        'what-is-fold': 'var(--chart-4-3)',
        'who-is-silentmode': 'var(--chart-4-4)',
        'credits': 'var(--chart-4-5)',

        'Other': "var(--chart-3-5)"
    };

    function getColor(key) {
        return fixedColors[key] ?? stringToColor(key);
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
        <section id="analyticsCollapseOpens" className="analytics-section">
            <HeadingSmall title="Collapse sections opened"/>

            <LoadingOverlay isLoading={isLoading}>
                <div className="flex flex-col lg:flex-row gap-8">
                    <div className="lg:w-3/5">
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
                                        fill={getColor(key)}
                                    />
                                ))}
                            </BarChart>
                        )}
                    </div>
                    <div className="lg:w-2/5">
                        <table className="data-table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col" className="text-left">Page</th>
                                <th scope="col" className="text-left">Section</th>
                                <th scope="col" className="text-right">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            {chartData ? chartData.table.map((row, index) => (
                                <tr key={index}>
                                    <th scope="row">
                                    <span className="block size-4"
                                          style={{ backgroundColor: getColor(row.section) }}></span>
                                    </th>
                                    <th className="text-left" scope="row">{row.page}</th>
                                    <th className="text-left" scope="row">{row.section}</th>
                                    <td className="text-right">{row.count}</td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="4" className="nothing">No data recorded.</td>
                                </tr>
                            )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </LoadingOverlay>
        </section>
    )
}
