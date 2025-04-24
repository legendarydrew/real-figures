import { CartesianGrid, Bar, BarChart, ResponsiveContainer, XAxis, YAxis } from "recharts";

// https://www.geeksforgeeks.org/create-a-line-chart-using-recharts-in-reactjs/

type DashboardSongPlaysData = {
    days: {
        date: string
        play_count: number;
    }[];
}

interface DashboardSongPlaysProps {
    data: DashboardSongPlaysData;
}

export const DashboardSongTotalPlays: React.FC<DashboardSongPlaysProps> = ({ data }) => {

    const labelStyle = {
        fontSize: 10,
        fontWeight: 'bold'
    };

    return (
        <section>
            <h2 className="font-bold mb-2">Song Plays <small>in the last week</small></h2>
            <ResponsiveContainer className="w-full h-[12rem]" aspect={2.5}>
                <BarChart data={data.days} margin={0}>
                    <CartesianGrid strokeDasharray="3 3"/>
                    <XAxis dataKey="date" type="category" tickCount={7} style={labelStyle} padding={{ top: 8 }}/>
                    <YAxis style={labelStyle}/>
                    <Bar dataKey="play_count" label="Total song plays"/>
                </BarChart>
            </ResponsiveContainer>
        </section>
    );
};
