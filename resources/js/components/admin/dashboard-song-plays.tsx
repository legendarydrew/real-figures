import { CartesianGrid, Line, LineChart, ResponsiveContainer, XAxis, YAxis } from "recharts";

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

export const DashboardSongPlays: React.FC<DashboardSongPlaysProps> = ({ data }) => {

    return (
        <section>
            <h2 className="font-bold mb-2">Song Plays <small>in the last week</small></h2>
            <ResponsiveContainer className="w-full h-[12rem]" aspect={3}>
                <LineChart data={data.days}>
                    <CartesianGrid strokeDasharray="3 3"/>
                    <XAxis dataKey="date" type="category"/>
                    <YAxis/>
                    <Line dataKey="play_count" label="Total song plays"/>
                </LineChart>
            </ResponsiveContainer>
        </section>
    );
};
