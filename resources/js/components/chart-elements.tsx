import { LabelProps, ReferenceLine, XAxis, YAxis } from 'recharts';
import { usePage } from '@inertiajs/react';

export const formatDate = (locale: string, timestamp: string): string => {
    return new Date(timestamp).toLocaleDateString(locale, {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

export const ChartDateXAxis: React.FC = () => {
    const { locale } = usePage().props;

    return (
        <XAxis dataKey="date" type="category" tickFormatter={(ts) => formatDate(locale, ts)} fontSize={10}/>
    )
};


export const ChartYAxis: React.FC = ({ label, ...props }) => {
    const labelProps: LabelProps = {
        value: label,
        angle: -90,
        fontSize: 12,
        fontWeight: 'bold',
        fill: 'var(--foreground)',
    };
    return (<YAxis fontSize={10} allowDecimals={false} label={labelProps} {...props} />)
};

export const ChartRoundReferences: React.FC = () => {
    const { rounds } = usePage().props;

    const formatReferenceLine = (round) => ({
        // value: round.name,
        content: (props) => <ChartReference round={round} props={props}/>,
        fill: 'var(--secondary)',
        position: 'insideBottomRight',
        angle: -90,
        fontSize: 10,
        fontWeight: 'bold',
        textAnchor: 'start'
    });

    return rounds.map((round) => (
        <ReferenceLine key={round.date} x={round.date}
                       stroke="var(--secondary)"
                       strokeWidth={2}
                       label={formatReferenceLine(round)}/>
    ));
};
