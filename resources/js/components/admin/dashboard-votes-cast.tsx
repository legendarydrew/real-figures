import { Bar, BarChart, CartesianGrid, ResponsiveContainer, XAxis, YAxis } from 'recharts';
import { Nothing } from '@/components/nothing';

type DashboardVotesCastData = {
    date: string;
    votes: number;
}[];

interface DashboardVotesCastProps {
    data: DashboardVotesCastData;
    className?: string;
}

export const DashboardVotesCast: React.FC<DashboardVotesCastProps> = ({ data, className }) => {

    const labelStyle = {
        fontSize: 10,
        fontWeight: 'bold'
    };

    return (
        <div className={className}>
            {data.length ? (
                <>
                    <h2 className="font-bold mb-2">Votes cast <small>within the last week</small></h2>
                    <ResponsiveContainer className="w-full h-[12rem]" aspect={2.5}>
                        <BarChart data={data} margin={2}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <XAxis dataKey="date" style={labelStyle} padding={{ top: 8 }}/>
                            <YAxis style={labelStyle}/>
                            <Bar dataKey="votes" label="Votes cast"/>
                        </BarChart>
                    </ResponsiveContainer>
                </>) : (
                <Nothing className="border-2 w-full h-full">
                    No information about votes cast.
                </Nothing>
            )}
        </div>
    );
};
