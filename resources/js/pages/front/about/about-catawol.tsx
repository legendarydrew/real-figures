import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronDown, ChevronUp } from 'lucide-react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import HeadingSmall from '@/components/heading-small';
import { useState } from 'react';

export const AboutCatawolPanel: React.FC = () => {

    const [isOpen, setIsOpen] = useState(false);

    return (
        <Collapsible className="border-b" open={isOpen} onOpenChange={() => setIsOpen(!isOpen)}>
            <CollapsibleTrigger
                className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                <span className="display-text flex-grow text-left">About CATAWOL Records</span>
                {isOpen ? <ChevronUp/> : <ChevronDown/>}
            </CollapsibleTrigger>
            <CollapsibleContent
                className="content pt-3 pb-5 lg:pb-10 px-2 lg:px-5 flex flex-col md:flex-row gap-5 lg:gap-10">
                <PlaceholderPattern className="md:w-2/5 stroke-neutral-900/20"/>
                <div className="md:w-3/5">
                    <p>
                        <b className="text-current">CATAWOL Records is a fictitious music label,</b> created as
                        the subject of
                        SilentMode's first ever LEGO modular building, back in 2011.
                    </p>

                    <div className="text-sm">
                        <HeadingSmall title="Overview"/>
                        <p>
                            CATAWOL Records is a globally influential record label that has shaped the music
                            industry for over seventy years. Founded in 1953 by industry magnates Henry
                            Cardinal,
                            Simon Tate, and Reginald Wolseley, the label has been both lauded for its altruism
                            and
                            criticised for its commercial approach to talent development. CATAWOL has played a
                            pivotal role in launching and sustaining the careers of some of the biggest names in
                            music, though not without controversy.
                        </p>

                        <HeadingSmall title="History"/>
                        <p>
                            In its early years, CATAWOL Records was instrumental in introducing groundbreaking
                            artists to the public. Originally conceived as an independent label with an
                            artist-first
                            approach, it quickly gained traction for its ability to recognise and amplify
                            emerging
                            talent. By the late 1960s, CATAWOL had established itself as a dominant force in the
                            music business, acquiring smaller labels and solidifying its influence across
                            multiple
                            genres.
                        </p>
                        <p>
                            During the 1980s and 1990s, the label fully embraced the concept of manufactured
                            celebrities, investing heavily in market-driven talent scouting, production, and
                            branding. This approach allowed CATAWOL to maintain its commercial dominance, but it
                            also drew criticism from purists who accused the company of prioritising
                            profitability
                            over artistic integrity.
                        </p>
                        <p>
                            Today, only one of the founder's families remains directly involved in the label,
                            maintaining a significant role in its operations and strategic direction.
                        </p>

                        <HeadingSmall title="Business Model and Influence"/>
                        <p>
                            CATAWOL Records operates as a multi-tiered entertainment conglomerate, encompassing
                            artist management, production, publishing, and merchandising. The label has been
                            known for its strategic partnerships with major media corporations, ensuring
                            widespread promotion for its artists.
                        </p>
                        <p>
                            Despite its calculated commercial approach, CATAWOL has also engaged in
                            philanthropic initiatives, funding music education programs and supporting
                            independent artists through subsidiary labels. This duality &ndash; balancing
                            profit-driven
                            operations with charitable endeavours &ndash; has led to the label being described
                            as a
                            "necessary evil" within the industry.
                        </p>
                    </div>
                </div>
            </CollapsibleContent>
        </Collapsible>

    );
}
