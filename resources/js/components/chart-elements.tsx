import { LabelProps, ReferenceLine, XAxis, YAxis } from 'recharts';
import { usePage } from '@inertiajs/react';
import { RefObject, useEffect, useRef } from 'react';
import { formatDate } from '@/lib/utils';

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
        fill: 'var(--foreground)'
    };
    return (<YAxis fontSize={10} allowDecimals={false} label={labelProps} {...props} />)
};

/**
 * A component used for drawing a ReferenceLine label.
 * We went down this route because I wanted a background rectangle for the text, and we have to
 * calculate the width based on the length of the text - which we won't know until it has been
 * rendered.
 *
 * @param round the data representing the Round (date and name).
 * @param props the properties of the ReferenceLine label.
 * @constructor
 */
const ChartReference: React.FC = ({ round, props }) => {
    const text = useRef(null);
    const boxWidth: RefObject<number> = useRef<number>(0);
    const boxHeight: RefObject<number> = useRef<number>(16);

    console.log(round);
    useEffect(() => {
        if (text.current) {
            boxWidth.current = text.current.scrollWidth + props.offset * 2;
        }
    }, [text, props.offset]);

    return (
        <g transform={`translate(${props.viewBox.x - props.offset},${props.viewBox.height - props.offset})rotate(${props.angle})`}>
            <rect fill="var(--secondary)" opacity={0.75} x={-props.offset} y={-props.offset * 2}
                  width={boxWidth.current} height={boxHeight.current}></rect>
            <text ref={text} fontSize={props.fontSize} fontWeight="bold" fill="#FFF">{round.name}</text>
        </g>);
};

export const ChartRoundReferences: React.FC = () => {
    const { rounds } = usePage().props;

    const formatReferenceLine = (round) => ({
        // value: round.name,
        content: (props) => (<ChartReference round={round} props={props}/>),
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
