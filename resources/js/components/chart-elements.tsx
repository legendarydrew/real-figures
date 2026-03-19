import { LabelProps, ReferenceLine, XAxis, YAxis } from 'recharts';
import { usePage } from '@inertiajs/react';
import { RefObject, useEffect, useRef } from 'react';
import { cssVar, formatDate } from '@/lib/utils';

export const ChartDateXAxis: React.FC = () => {
    const { locale } = usePage().props;

    return (
        <XAxis dataKey="date" type="category"
               tickFormatter={(ts) => formatDate(locale, ts)} fontSize={10}/>
    )
};

/**
 * A component used for drawing a ReferenceLine label.
 * We went down this route because I wanted a background rectangle for the text, and we have to
 * calculate the width based on the length of the text - which we won't know until it has been
 * rendered.
 *
 * @param label the text to display.
 * @param colour the background colour to use.
 * @param props the properties of the ReferenceLine label.
 * @constructor
 */
function ChartReference(label, colour, props) {
    const text = useRef(null);
    const boxWidth: RefObject<number> = useRef<number>(0);
    const boxHeight: RefObject<number> = useRef<number>(16);

    useEffect(() => {
        if (text.current) {
            boxWidth.current = text.current.scrollWidth + props.offset * 2;
        }
    }, [text, props.offset]);

    return (
        <g transform={`translate(${props.viewBox.x - props.offset},${props.viewBox.height - props.offset})rotate(${props.angle})`}>
            <rect fill={colour} opacity={0.75} x={-props.offset} y={-props.offset * 2}
                  width={boxWidth.current} height={boxHeight.current}></rect>
            <text ref={text} fontSize={props.fontSize} fontWeight="bold" fill="#FFF">{label}</text>
        </g>);
};

export const ChartRoundReferences: React.FC = () => {
    const { markers } = usePage().props;

    const formatReferenceLine = (label, colour) => ({
        // value: label,
        content: (props) => ChartReference(label, colour, props),
        fill: colour,
        position: 'insideBottomRight',
        angle: -90,
        fontSize: 10,
        fontWeight: 'bold',
        textAnchor: 'start'
    });

    return markers && (<>
            {markers.stages.map((stage) => (
                <ReferenceLine key={stage.name} x={stage.start} stroke="red" strokeWidth={2}
                               label={formatReferenceLine(stage.name, 'red')}/>
            ))}
            {markers.rounds.map((round) => (
                <ReferenceLine key={round.name} x={round.date}
                               stroke="var(--secondary)"
                               strokeWidth={2}
                               label={formatReferenceLine(round.name, cssVar('--secondary'))}></ReferenceLine>
            ))}
            {markers.over && (
                <ReferenceLine x={markers.over} stroke="blue" strokeWidth={2}
                               label={formatReferenceLine('Contest over', 'blue')}></ReferenceLine>
            )}
        </>
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

