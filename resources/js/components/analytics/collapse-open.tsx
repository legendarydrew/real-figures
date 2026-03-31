import { Bar, BarChart, CartesianGrid } from 'recharts';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { stringToChartColour } from '@/lib/utils';


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
        if (isLoading) {
            return;
        }
        setIsLoading(true);
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
        'the-golden-buzzer': 'var(--gold-light)',
        'special-situations': 'var(--chart-1-8)',
        'advice-for-visitors': 'var(--chart-1-9)',

        // About
        'about-catawol-records': 'var(--chart-4-1)',
        'about-the-song': 'var(--chart-4-2)',
        'what-is-fold': 'var(--destructive)',
        'who-is-silentmode': 'var(--primary)',
        'credits': 'var(--chart-4-5)'
    };

    function getColor(key) {
        return fixedColors[key] ?? stringToChartColour(key);
    }

    return (
        <section id="analyticsCollapseOpens" className="analytics-section">
            <h2 className="analytics-section-title">Collapse sections opened</h2>

            <LoadingOverlay isLoading={isLoading}>

                {chartData && (
                    <BarChart data={chartData.data} style={{ width: '100%', maxHeight: '300px', aspectRatio: 3 }}
                              responsive
                              margin={2}>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Opened count"/>

                        {chartData.keys.map(key => (
                            <Bar
                                key={key}
                                dataKey={key}
                                stackId="sections"
                                fill={getColor(key)}
                            />
                        ))}
                        <ChartRoundReferences position="start"/>
                    </BarChart>
                )}

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
            </LoadingOverlay>
        </section>
    )
}
