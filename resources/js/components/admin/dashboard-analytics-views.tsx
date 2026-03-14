import { CartesianGrid, Line, LineChart, ReferenceLine, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { cssVar } from '@/lib/utils';
import { useEffect, useRef } from 'react';

type DashboardAnalyticsViewsData = {
    date: string;
    views: number;
    visitors: number;
}[];

interface DashboardAnalyticsViewsProps {
    data: DashboardAnalyticsViewsData;
    className?: string;
}

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
const ChartReference = ({ round, props }) => {
    const text = useRef();
    const boxWidth = useRef<number>(0);
    const boxHeight = useRef<number>(16);

    useEffect(() => {
        if (text.current) {
            boxWidth.current = text.current.scrollWidth + props.offset * 2;
        }
    }, [text, props.offset]);

    return (
        <g transform={`translate(${props.viewBox.x - props.offset},${props.viewBox.height - props.offset})rotate(${props.angle})`}>
            <rect fill="var(--secondary)" opacity={0.75} x={-props.offset} y={-props.offset * 2} width={boxWidth.current} height={boxHeight.current}></rect>
            <text ref={text} fontSize={props.fontSize} fontWeight="bold" fill="#FFF">{round.name}</text>
        </g>);
} ;

export const DashboardAnalyticsViews: React.FC<DashboardAnalyticsViewsProps> = ({ data, className }) => {

    const { locale, rounds } = usePage().props;

    const formatDate = (timestamp: string): string => {
        return new Date(timestamp).toLocaleDateString(locale as string);
    };

    const formatReferenceLine = (round) => ({
        // value: round.name,
        content: (props) => <ChartReference round={round} props={props} />,
        fill: 'var(--secondary)',
        position: 'insideBottomRight',
        angle: -90,
        fontSize: 10,
        fontWeight: 'bold',
        textAnchor: 'start'
    });

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-3">
                    <span className="display-text">{formatDate(label)}</span>
                    <span className="text-sm">
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'page view' : 'page views'}
                    </span>
                    <span className="text-sm">
                        {payload[1].value ? payload[1].value.toLocaleString() : 'No'} {payload[0].value === 1 ? 'Visitor' : 'Visitors'}
                    </span>
                </div>
            );
        }

        return null;
    };

    return (
        <Card className={className}>
            <CardTitle className="display-text font-normal">
                Page views <small>within the last week</small>
            </CardTitle>
            <CardContent className="px-0">
                {data.length ? (
                    <ResponsiveContainer className="w-full" aspect={4}>
                        <LineChart data={data} margin={2}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <XAxis dataKey="date"
                                   tickFormatter={formatDate}
                                   className="display-text font-normal text-xs"/>
                            <YAxis className="display-text font-normal text-xs"/>
                            <Tooltip content={tooltipContent} isAnimationActive={false}/>
                            <Line dataKey="views" label="Page views" dot={false} strokeWidth={2}
                                  stroke={cssVar('--primary')}/>
                            <Line dataKey="visitors" label="Visitors" dot={false} strokeWidth={2}
                                  stroke="var(--secondary')"/>

                            {rounds.map((round) => (
                                <ReferenceLine key={round.date} x={round.date}
                                               stroke="var(--secondary)"
                                               strokeWidth={2}
                                               label={formatReferenceLine(round)}/>
                            ))}
                        </LineChart>
                    </ResponsiveContainer>
                ) : (
                    <Nothing>
                        No page views information.
                    </Nothing>
                )}
            </CardContent>
        </Card>
    );
};
