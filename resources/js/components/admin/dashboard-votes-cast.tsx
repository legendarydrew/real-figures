import { Bar, BarChart, CartesianGrid, Tooltip } from 'recharts';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { cssVar, formatDate } from '@/lib/utils';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';

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
            <CardHeader>
                <CardTitle>Votes cast <small>within the last week</small></CardTitle>
            </CardHeader>
            <CardContent>
                {data.length ? (
                    <BarChart responsive data={data} width="100%" height={200}>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Votes"/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <Bar dataKey="votes" label="Votes cast"
                             radius={[4, 4, 0, 0]}
                             fill={cssVar('--chart-4-4')}/>
                        <ChartRoundReferences/>
                    </BarChart>
                ) : (
                    <Nothing>No information about votes cast.</Nothing>
                )}
            </CardContent>
        </Card>
    );
};
