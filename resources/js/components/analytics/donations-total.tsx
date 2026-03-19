import { Area, AreaChart, ReferenceLine } from 'recharts';
import HeadingSmall from '@/components/heading-small';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartYAxis } from '@/components/chart-elements';


interface Props {
    days?: number
}

export const DonationsTotalAnalytics: React.FC<Props> = ({ days = 7 }) => {

    const { donation } = usePage().props;

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
                {chartData?.data.length ? (
                    <AreaChart style={{ width: '100%', maxHeight: '300px', aspectRatio: 3 }}
                               responsive
                               data={chartData.data} margin={2}>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Total raised"/>
                        <Area dataKey="b" dot={false} strokeWidth={2} stackId="1"
                              stroke="var(--gold)" fill="var(--gold-light)"/>
                        <Area dataKey="d" dot={false} strokeWidth={2} stackId="1"
                              stroke="var(--donation)" fill="var(--donation-light)"/>
                        {donation.target && (
                            <ReferenceLine y={donation.target} stroke="var(--secondary)"
                                           label={`Target amount: ${donation.currency} ${donation.target}`}/>
                        )}
                    </AreaChart>
                ) : (
                    <Nothing>
                        No donation information.
                    </Nothing>
                )}
            </LoadingOverlay>
        </section>
    )
}
