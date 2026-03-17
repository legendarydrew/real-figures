import { Bar, BarChart, CartesianGrid, ResponsiveContainer, Tooltip } from 'recharts';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { cssVar } from '@/lib/utils';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis, formatDate } from '@/components/chart-elements';

type DashboardVotesCastData = {
    date: string;
    votes: number;
}[];

interface DashboardVotesCastProps {
    data: DashboardVotesCastData;
    className?: string;
}

export const DashboardVotesCast: React.FC<DashboardVotesCastProps> = ({ data, className }) => {

    const { locale } = usePage().props;


    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text text-sm">{formatDate(locale as string, label)}</span><br/>
                    <span className="text-xs">
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'vote' : 'votes'} cast
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">Votes cast <small>within the last week</small></CardTitle>
            <CardContent>
                {data.length ? (
                    <ResponsiveContainer width="100%" aspect={3.75} maxHeight={300}>
                        <BarChart data={data} height={300}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <ChartDateXAxis />
                            <ChartYAxis label="Votes" />
                            <Tooltip content={tooltipContent} isAnimationActive={false}/>
                            <Bar dataKey="votes" label="Votes cast"
                                 radius={[4, 4, 0, 0]}
                                 fill={cssVar('--chart-4-4')}/>
                            <ChartRoundReferences />
                        </BarChart>
                    </ResponsiveContainer>
                ) : (
                    <Nothing>No information about votes cast.</Nothing>
                )}
            </CardContent>
        </Card>
    );
};
