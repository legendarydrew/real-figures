import { LoaderCircleIcon } from 'lucide-react';
import { Bar, BarChart, Tooltip, XAxis, YAxis } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';


interface Props {
    days?: number
}

export const VotesAnalytics: React.FC<Props> = ({ days = 7 }) => {

    const [chartData, setChartData] = useState<AnalyticsData>();
    const [isLoading, setIsLoading] = useState<boolean>(false);

    useEffect(() => {
        fetchData();
    }, [days]);

    const fetchData = () => {
        if (isLoading) {
            return;
        }
        setIsLoading(true);
        return axios.get("/api/analytics/votes", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });

    }

    return (
        <section id="analyticsVotes" className="analytics-section">
            <HeadingSmall title="Votes cast"/>

            {/* TODO an overlay.*/}
            {isLoading && <LoaderCircleIcon/>}

            {chartData && (
                <BarChart
                    style={{ width: '100%', maxHeight: '300px', aspectRatio: 1.618 }}
                    responsive
                    data={chartData}
                >
                    <XAxis dataKey="time"/>
                    <YAxis/>
                    <Tooltip/>

                    <Bar dataKey="count" fill="var(--chart-2-6)"/>
                </BarChart>
            )}
            {/*<table className="data-table">*/}
            {/*    <thead>*/}
            {/*    <tr>*/}
            {/*        <th scope="col" className="text-left">Page</th>*/}
            {/*        <th scope="col" className="text-left">Section</th>*/}
            {/*        <th scope="col" className="text-right">Count</th>*/}
            {/*    </tr>*/}
            {/*    </thead>*/}
            {/*    <tbody>*/}
            {/*    {chartData ? chartData.table.map((row, index) => (*/}
            {/*        <tr key={index}>*/}
            {/*            <th className="text-left" scope="row">{row.page}</th>*/}
            {/*            <th className="text-left" scope="row">{row.section}</th>*/}
            {/*            <td className="text-right">{row.count}</td>*/}
            {/*        </tr>*/}
            {/*    )) : (*/}
            {/*        <tr>*/}
            {/*            <td colSpan="4" className="nothing">No data recorded.</td>*/}
            {/*        </tr>*/}
            {/*    )}*/}
            {/*    </tbody>*/}
            {/*</table>*/}
        </section>
    )
}
