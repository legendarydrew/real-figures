import { Area, AreaChart, ResponsiveContainer, XAxis, YAxis } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { LoadingOverlay } from '@/components/mode/loading-overlay';


interface Props {
    days?: number
}

export const DonationsTotalAnalytics: React.FC<Props> = ({ days = 7 }) => {

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
        return axios.get("/api/analytics/donations/total", { params: { days } })
            .then((res) => {
                setChartData(res.data);
            })
            .catch((res) => RTToast.error(res.message))
            .finally(() => {
                setIsLoading(false);
            });

    }

    const { locale } = usePage().props;

    const formatDate = (timestamp: string): string => {
        return new Date(timestamp).toLocaleDateString(locale);
    };

    return (
        <section id="analyticsVotes" className="analytics-section">
            <HeadingSmall title="Donations total"/>

            <LoadingOverlay isLoading={isLoading}>
                {chartData?.length ? (
                    <ResponsiveContainer className="w-full" aspect={4}>
                        <AreaChart data={chartData} margin={2}>
                            <XAxis dataKey="date"
                                   tickFormatter={formatDate}
                                   className="display-text font-normal text-xs"/>
                            <YAxis className="display-text font-normal text-xs"/>
                            <Area dataKey="total" dot={false} strokeWidth={2}
                                  stroke="var(--donation)" fill="var(--donation-light)"/>
                        </AreaChart>
                    </ResponsiveContainer>
                ) : (
                    <Nothing>
                        No donation information.
                    </Nothing>
                )}
            </LoadingOverlay>
        </section>
    )
}
