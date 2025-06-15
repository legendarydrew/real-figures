import { Bar, BarChart, CartesianGrid, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts';
import { Nothing } from '@/components/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardTitle } from '@/components/ui/card';

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

    const formatDate = (timestamp: string): string => {
        return new Date(timestamp).toLocaleDateString(locale);
    };

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text">{formatDate(label)}</span><br/>
                    <span
                        className=" text-sm">{payload[0].value ? payload[0].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'vote' : 'votes'} cast</span>
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
                    <ResponsiveContainer className="w-full h-[12rem]" aspect={3.75}>
                        <BarChart data={data} margin={2}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <XAxis dataKey="date"
                                   tickFormatter={formatDate}
                                   className="display-text font-normal text-xs"/>
                            <YAxis className="display-text font-normal text-xs"/>
                            <Tooltip content={tooltipContent} isAnimationActive={false}/>
                            <Bar dataKey="votes" label="Votes cast"
                                 radius={[4, 4, 0, 0]}
                                 className="fill-zinc-500"/>
                        </BarChart>
                    </ResponsiveContainer>
                ) : (
                    <Nothing className="border-2 w-full h-full">
                        No information about votes cast.
                    </Nothing>
                )}
            </CardContent>
        </Card>
    );
};
