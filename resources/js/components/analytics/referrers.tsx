import { LoaderCircleIcon } from 'lucide-react';
import HeadingSmall from '@/components/heading-small';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { RTToast } from '@/components/mode/toast-message';
import { AnalyticsData } from '@/types';


interface Props {
    days?: number;
}

export const ReferrersAnalytics: React.FC<Props> = ({ days = 7 }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [chartData, setChartData] = useState<AnalyticsData>();

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        axios.get("/api/analytics/referrers", { params: { days } })
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

    return (
        <section id="analyticsReferrers" className="analytics-section">
            <HeadingSmall title="Referrers"/>

            {/* TODO an overlay.*/}
            {isLoading && <LoaderCircleIcon/>}

            <div className="grid lg:grid-cols-2 gap-8">

                {/*{chartData && (*/}
                {/*    <BarChart*/}
                {/*        style={{ width: '100%', maxHeight: '300px', aspectRatio: 1.618 }}*/}
                {/*        responsive*/}
                {/*        data={chartData.data}*/}
                {/*    >*/}
                {/*        <XAxis dataKey="date"/>*/}
                {/*        <YAxis/>*/}

                {/*        {chartData.keys.map(key => (*/}
                {/*            <Bar*/}
                {/*                key={key}*/}
                {/*                dataKey={key}*/}
                {/*                stackId="sections"*/}
                {/*                fill={getColor(key)}*/}
                {/*            />*/}
                {/*        ))}*/}
                {/*    </BarChart>*/}
                {/*)}*/}
                <table className="data-table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col" className="text-left">Referrer</th>
                        <th scope="col" className="text-right">Count</th>
                    </tr>
                    </thead>
                    <tbody>
                    {chartData ? chartData.table.map((row, index) => (
                        <tr key={index}>
                            <th scope="row">
                                <span className="block size-4"
                                      style={{ backgroundColor: stringToColor(row.referrer) }}></span>
                            </th>
                            <th className="text-left" scope="row">{row.referrer.length ? row.referrer : (
                                <em className="text-muted-foreground font-normal">none</em>)}</th>
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
        </section>
    )
}
