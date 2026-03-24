import { LabelProps, ReferenceLine, XAxis, YAxis } from 'recharts';
import { usePage } from '@inertiajs/react';
import { cssVar, formatDate, formatDateHour } from '@/lib/utils';

export const ChartDateXAxis: React.FC = () => {
    const { locale } = usePage().props;

    return (
        <XAxis dataKey="date" type="category"
               tickFormatter={(ts) => formatDate(locale, ts)} fontSize={10}/>
    )
};

export const ChartTimeXAxis: React.FC = () => {
    const { locale } = usePage().props;

    return (
        <XAxis dataKey="time" type="category"
               tickFormatter={(ts) => formatDateHour(locale, ts)} fontSize={10}/>
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
    return (
        <g transform={`translate(${props.viewBox.x - props.offset},${props.viewBox.height - props.offset})rotate(${props.angle})`}>
            <text fontSize={props.fontSize} fontWeight="bold" fill={colour}>{label}</text>
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
        dx: props.orientation === 'right' ? 12 : -12,
        fontSize: 12,
        fontWeight: 'bold',
        fill: props.fill ?? 'var(--foreground)'
    };
    return (<YAxis type="number" fontSize={10} allowDecimals={false} label={labelProps} {...props} />)
};

