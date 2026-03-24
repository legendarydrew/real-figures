import { CartesianGrid, Line, LineChart } from 'recharts';
import { useEffect, useState } from 'react';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';


interface Props {
    days?: number
}

export const DummyAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [chartData, setChartData] = useState<any[]>();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        setChartData([
            { date: '2026-03-13T00:00:00.000000Z', value: 6 },
            { date: '2026-03-14T00:00:00.000000Z', value: 2 },
            { date: '2026-03-15T00:00:00.000000Z', value: 7 },
            { date: '2026-03-16T00:00:00.000000Z', value: 8 },
            { date: '2026-03-17T00:00:00.000000Z', value: 2 },
            { date: '2026-03-18T00:00:00.000000Z', value: 4 },
            { date: '2026-03-19T00:00:00.000000Z', value: 5 },
            { date: '2026-03-20T00:00:00.000000Z', value: 1 },
            { date: '2026-03-21T00:00:00.000000Z', value: 3 },
            { date: '2026-03-22T00:00:00.000000Z', value: 2 },
            { date: '2026-03-23T00:00:00.000000Z', value: 5 },
            { date: '2026-03-24T00:00:00.000000Z', value: 2 }
        ]);
    }

    return (
        <section id="analyticsDummy" className="analytics-section">
            <h2 className="analytics-section-title">Dummy analytics</h2>

                <LineChart data={chartData} style={{ width: '100%', height: '240px', aspectRatio: 3 }}
                           responsive
                           margin={2}>
                    <CartesianGrid strokeDasharray="3 3"/>
                    <ChartDateXAxis/>
                    <ChartYAxis/>
                    <Line dataKey="value" label="Value"/>
                    <ChartRoundReferences/>
                </LineChart>
        </section>
    )
}
