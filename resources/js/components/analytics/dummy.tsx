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
            {
                "date": "2026-03-17T00:00:00.000000Z",
                "activeUsers": 1,
                "screenPageViews": 48
            },
            {
                "date": "2026-03-18T00:00:00.000000Z",
                "activeUsers": 1,
                "screenPageViews": 14
            },
            {
                "date": "2026-03-19T00:00:00.000000Z",
                "activeUsers": 1,
                "screenPageViews": 6
            },
            {
                "date": "2026-03-20T00:00:00.000000Z",
                "activeUsers": 1,
                "screenPageViews": 2
            },
            {
                "date": "2026-03-21T00:00:00.000000Z",
                "screenPageViews": 0,
                "activeUsers": 0
            },
            {
                "date": "2026-03-22T00:00:00.000000Z",
                "screenPageViews": 0,
                "activeUsers": 0
            },
            {
                "date": "2026-03-23T00:00:00.000000Z",
                "screenPageViews": 0,
                "activeUsers": 0
            },
            {
                "date": "2026-03-24T00:00:00.000000Z",
                "screenPageViews": 0,
                "activeUsers": 0
            }
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
                    <Line dataKey="screenPageViews" label="Value"/>
                    <ChartRoundReferences/>
                </LineChart>
        </section>
    )
}
