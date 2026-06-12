import { Bar, BarChart, XAxis } from 'recharts';

interface Props {
    results: { acts: any[], breakdown: { id: number, O: number, M, number, D: number }[] }
}

export const RoundVoteBreakdownChart: React.FC<Props> = ({ results }) => {

    const actImageWidth: number = 48;

    const CustomXAxisLabel = (props) => {
        const act_id = props.payload.value;
        const act = results.acts.find((row) => row.id === act_id);
        return (
            <g transform={`translate(${props.x - actImageWidth/2},${props.y})`}>
                <rect width={actImageWidth} height={actImageWidth} fill="var(--color-secondary)" />
                <image xlinkHref={act.image} x={0} y={0} height={actImageWidth} width={actImageWidth} />
            </g>
        );
    };

    return results ? (
        <BarChart
            style={{ width: '100%', maxHeight: '180px', aspectRatio: 3 }}
            responsive
            data={results.breakdown}>
            <XAxis dataKey="id" type="category" tick={CustomXAxisLabel} tickLine={false} height={actImageWidth + 12} />
            <Bar stackId="score" dataKey="O" maxBarSize={actImageWidth} fill="var(--chart-1-9)"/>
            <Bar stackId="score" dataKey="M" maxBarSize={actImageWidth} fill="var(--indigo-700)"/>
            <Bar stackId="score" dataKey="D" maxBarSize={actImageWidth} fill="var(--green-500)"/>
        </BarChart>
    ) : '';
};
