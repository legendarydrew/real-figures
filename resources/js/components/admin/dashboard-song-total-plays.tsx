import { Bar, BarChart, CartesianGrid, Tooltip } from "recharts";
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { cssVar, formatDate } from '@/lib/utils';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis } from '@/components/chart-elements';

// https://www.geeksforgeeks.org/create-a-line-chart-using-recharts-in-reactjs/

type DashboardSongPlaysData = {
    days: {
        date: string
        play_count: number;
    }[];
}

interface DashboardSongPlaysProps {
    data: DashboardSongPlaysData;
    className?: string;
}

export const DashboardSongTotalPlays: React.FC<DashboardSongPlaysProps> = ({ data, className }) => {

    const { locale } = usePage().props;

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text text-sm">{formatDate(locale as string, label)}</span><br/>
                    <span className="text-xs">
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'} Song {payload[0].value === 1 ? 'play' : 'plays'}
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <Card className={className}>
            <CardHeader>
                <CardTitle>Song plays <small>within the last week</small></CardTitle>
            </CardHeader>
            <CardContent>
                {data.days.length ? (
                    <BarChart responsive data={data.days} width="100%" height={200}>
                        <CartesianGrid strokeDasharray="3 3"/>
                        <ChartDateXAxis/>
                        <ChartYAxis label="Song plays"/>
                        <Tooltip content={tooltipContent} isAnimationActive={false}/>
                        <Bar dataKey="play_count" label="Total song plays"
                             radius={[4, 4, 0, 0]}
                             fill={cssVar('--chart-1-4')}/>
                        <ChartRoundReferences/>
                    </BarChart>
                ) : (
                    <Nothing>No information about Songs played.</Nothing>
                )}
            </CardContent>
        </Card>);
};
