import { CartesianGrid, Line, LineChart, ResponsiveContainer, Tooltip } from 'recharts';
import { Nothing } from '@/components/mode/nothing';
import { usePage } from '@inertiajs/react';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import { cssVar } from '@/lib/utils';
import { useEffect, useRef } from 'react';
import { ChartDateXAxis, ChartRoundReferences, ChartYAxis, formatDate } from '@/components/chart-elements';

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
            <rect fill="var(--secondary)" opacity={0.75} x={-props.offset} y={-props.offset * 2}
                  width={boxWidth.current} height={boxHeight.current}></rect>
            <text ref={text} fontSize={props.fontSize} fontWeight="bold" fill="#FFF">{round.name}</text>
        </g>);
};

export const DashboardAnalyticsViews: React.FC<DashboardAnalyticsViewsProps> = ({ data, className }) => {

    const { locale } = usePage().props;

    const tooltipContent = ({ active, payload, label }) => {
        if (active && payload?.length) {
            return (
                <div className="bg-white flex flex-col gap-0 shadow-md leading-tight rounded-sm p-2">
                    <span className="display-text text-sm">{formatDate(locale as string, label)}</span>
                    <span className="flex items-center gap-1 text-xs">
                        <span className="size-3 inline-block bg-(--chart-4-3)"></span>
                        {payload[0].value ? payload[0].value.toLocaleString() : 'No'}&nbsp;
                        {payload[0].value === 1 ? 'page view' : 'page views'}
                    </span>
                    <span className="flex items-center gap-1 text-xs">
                        <span className="size-3 inline-block bg-(--chart-2-3)"></span>
                        {payload[1].value ? payload[1].value.toLocaleString() : 'No'}&nbsp;
                        {payload[1].value === 1 ? 'Visitor' : 'Visitors'}
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
            <CardContent>
                {data.length ? (
                    <ResponsiveContainer width="100%" aspect={3} maxHeight={300}>
                        <LineChart data={data} height={300}>
                            <CartesianGrid strokeDasharray="3 3"/>
                            <ChartDateXAxis/>
                            <ChartYAxis dataKey="views" yAxisId="pageViews" label="Views"/>
                            <ChartYAxis dataKey="visitors" yAxisId="pageVisitors" label="Visitors" orientation="right"/>

                            <Tooltip content={tooltipContent} isAnimationActive={false}/>
                            <Line dataKey="views" yAxisId="pageViews" label="Page views" dot={false} strokeWidth={2}
                                  stroke={cssVar('--chart-4-3')}/>
                            <Line dataKey="visitors" yAxisId="pageVisitors" label="Visitors" dot={false} strokeWidth={2}
                                  stroke={cssVar('--chart-2-3')}/>

                            <ChartRoundReferences/>
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
