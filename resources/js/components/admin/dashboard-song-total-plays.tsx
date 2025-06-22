import { Bar, BarChart, CartesianGrid, ResponsiveContainer, Tooltip, XAxis, YAxis } from "recharts";
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardTitle } from '@/components/ui/card';

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

    const formatDate = (timestamp: string): string => {
        return new Date(timestamp).toLocaleDateString(locale);
    };

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text">{formatDate(label)}</span><br/>
                    <span
                        className=" text-sm">{payload[0].value ? payload[0].value.toLocaleString() : 'No'} Song {payload[0].value === 1 ? 'play' : 'plays'}</span>
                </div>
            );
        }

        return null;
    };

    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">Song plays <small>within the last week</small></CardTitle>
            <CardContent>
                {data.days.length ? (
                    <ResponsiveContainer className="w-full h-[12rem]" aspect={2.5}>
                        <BarChart data={data.days} margin={0}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <XAxis dataKey="date" type="category" tickCount={7}
                                   tickFormatter={formatDate}
                                   className="display-text font-normal text-xs"/>
                            <YAxis className="display-text font-normal text-xs"/>
                            <Tooltip content={tooltipContent} isAnimationActive={false}/>
                            <Bar dataKey="play_count" label="Total song plays"
                                 radius={[4, 4, 0, 0]}
                                 className="fill-indigo-500"/>
                        </BarChart>
                    </ResponsiveContainer>
                ) : (
                    <Nothing className="border-2 w-full h-full">
                        No information about Songs played.
                    </Nothing>
                )}
            </CardContent>
        </Card>);
};
