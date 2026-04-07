import { Area, AreaChart, CartesianGrid, ReferenceLine, Tooltip } from 'recharts';
import { RTToast } from '@/components/mode/toast-message';
import { useEffect, useState } from 'react';
import axios from 'axios';
import { AnalyticsData } from '@/types';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { LoadingOverlay } from '@/components/mode/loading-overlay';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';
import { cssVar, formatDate } from '@/lib/utils';


interface Props {
    days?: number
}

export const DonationsTotalAnalytics: React.FC<Props> = ({ days = 7 }) => {

    const { donation, locale } = usePage().props;

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
            .catch((error) => RTToast.error(error.message))
            .finally(() => {
                setIsLoading(false);
            });

    }

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-2">
                    <span className="display-text text-sm">{formatDate(locale as string, label)}</span>
                    <span className="flex items-center gap-1 text-xs">
                        <span className="size-3 inline-block bg-(--donation-light)"></span>
                        Donations: {donation.currency} {payload[1].value.toLocaleString()}
                    </span>
                    <span className="flex items-center gap-1 text-xs">
                        <span className="size-3 inline-block bg-(--gold-light)"></span>
                        Golden Buzzers: {donation.currency} {payload[0].value.toLocaleString()}
                    </span>
                </div>
            );
        }

        return null;
    };

    const formatTargetReferenceLine = () => ({
        value: `Target amount: ${donation.currency} ${donation.target}`,
        fill: cssVar('--donation'),
        position: 'insideBottomLeft',
        fontSize: 11,
        fontWeight: 'bold',
        textAnchor: 'start'
    });

    return (
        <section id="analyticsDonationsTotal" className="analytics-section">
            <h2 className="analytics-section-title">Donations total</h2>

            <LoadingOverlay isLoading={isLoading}>
                {chartData?.data.length ? (
                    <AreaChart style={{ width: '100%', maxHeight: '300px', aspectRatio: 3 }}
                               responsive
                               data={chartData.data} margin={2}>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Total raised"/>
                        <Area dataKey="b" dot={false} strokeWidth={2} stackId="1"
                              stroke="var(--gold)" fill="var(--gold-light)"/>
                        <Area dataKey="d" dot={false} strokeWidth={2} stackId="1"
                              stroke="var(--donation)" fill="var(--donation-light)"/>
                        {donation.target && (
                            <ReferenceLine y={donation.target} stroke="var(--secondary)"
                                           label={formatTargetReferenceLine()}/>
                        )}
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <ChartRoundReferences/>
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
