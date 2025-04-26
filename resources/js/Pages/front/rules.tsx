import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Head } from '@inertiajs/react';
import { FrontHeader } from '@/components/front/front-header';
import { FrontContent } from '@/components/front/front-content';
import { FrontFooter } from '@/components/front/front-footer';
import Heading from '@/components/heading';
import HeadingSmall from '@/components/heading-small';
import { useState } from 'react';
import { ChevronDown, ChevronUp } from 'lucide-react';

export default function RulesPage() {

    const [panelState, setPanelState] = useState({});

    const toggleHandler = (section: string): void => {
        setPanelState({ ...section, [section]: !panelState[section] });
    };

    const isOpen = (section: string): boolean => {
        return panelState[section] ?? false;
    }

    return (
        <>
            <Head title="Contest Rules"/>
            <div
                className="flex h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">

                <FrontHeader/>
                <FrontContent>
                    <Heading title="Real Figures Don't F.O.L.D &ndash; Contest Rules"/>
                    <p className="text-lg my-3 text-muted-foreground">
                        In the first ever event of its kind, CATAWOL Records has enlisted 32 of the label's biggest acts
                        in a
                        song Contest: showcasing creativity and musical talent, whilst raising awareness of <em>bullying
                        in adult spaces</em>.
                    </p>
                    <p className="mb-3">
                        We need your help to decide which Act's version of the song - penned by the MODE Family's Sigfig
                        - will
                        become the official anthem.
                    </p>

                    <Collapsible className="border-b" open={isOpen('brief')}
                                 onOpenChange={() => toggleHandler('brief')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">Brief,
                            Criteria,
                            Eligibility
                            {isOpen('brief') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <p className="text-muted-foreground mb-3">We believe that a Contest that does not have a
                                brief, eligibility and criteria statement <em>as a minimum</em> is <em>potentially
                                    rigged</em>.</p>

                            <HeadingSmall title="The Brief"/>
                            <p className="mb-3">Create an original song, using the lyrics written by the MODE Family's
                                Sigfig, for
                                raising
                                awareness of bullying.</p>

                            <HeadingSmall title="Eligibility"/>
                            <ul className="list-disc my-3 mx-8">
                                <li>Entry is open to all Acts signed with CATAWOL Records.</li>
                                <li>Acts must be signed with CATAWOL Records for the duration of the Contest.</li>
                                <li>A maximum of 32 Acts will be chosen for the Contest.</li>
                            </ul>

                            <HeadingSmall title="Criteria"/>
                            <ul className="list-disc my-3 mx-8">
                                <li>Acts are free to interpret the song as they choose, while respecting the original
                                    lyrics.
                                </li>
                                <li>Each Act can only enter a single Song into the Contest.</li>
                                <li>All Songs entered must feature vocals from and be the work of the associated Act.
                                </li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('knockout-stage')}
                                 onOpenChange={() => toggleHandler('knockout-stage')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">Stage
                            1: Knockout
                            Stage
                            {isOpen('knockout-stage') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <HeadingSmall title="Submissions"/>
                            <p className="mb-3">Each of the 32 Acts in this Stage must submit a Song based on the
                                provided lyrics.</p>
                            <ul className="list-disc my-3 mx-8">
                                <li>The maximum duration of each Song is two minutes and ten seconds (2:10).</li>
                                <li>Each Song must include the provided lyrics, allowing for minor adjustments to match
                                    the style of the Song.
                                </li>
                            </ul>
                            <p className="mb-3">The Songs will be divided into 8 rounds, with 4 Songs in each round. The
                                allocation and
                                order of the Songs will be randomised.</p>

                            <HeadingSmall title="Voting"/>
                            <ul className="list-disc my-3 mx-8">
                                <li>Voting for each Round is open to all Visitors, excluding:
                                    <ul className="list-disc my-3 mx-8">
                                        <li>members of the F.O.L.D;</li>
                                        <li>those related to or affiliated with any of the competing Acts;</li>
                                        <li>those involved with the administration of the Contest;</li>
                                        <li>employees of CATAWOL Records.</li>
                                    </ul>
                                </li>
                                <li>Visitors will be asked to vote for their top three Songs in each Round.</li>
                                <li>Votes for each Round will only be counted while the respective Round is open.</li>
                            </ul>

                            <HeadingSmall title="Advancement"/>
                            <p className="mb-3">A total of ten Songs will advance to the second and final Stage:</p>
                            <ul className="list-disc mb-3 mx-8">
                                <li>the top Song from each Round (8 winners total);</li>
                                <li>The two highest-scoring runner-up Songs across all Rounds.</li>
                            </ul>
                            <p className="mb-3">The Acts associated with the winning Songs in this Stage will be further
                                developed
                                with:</p>
                            <ul className="list-disc mb-3 mx-8">
                                <li>a backstory;</li>
                                <li>an updated profile picture.</li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('final-stage')}
                                 onOpenChange={() => toggleHandler('final-stage')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Stage 2: Finals
                            {isOpen('final-stage') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <HeadingSmall title="Resubmission"/>
                            <p className="mb-3">Each of the ten Acts in this Stage must submit
                                an <em>updated</em> version of their Song.</p>
                            <ul className="list-disc mb-3 mx-8">
                                <li>As well as the originally provided lyrics, each Song must also include a <em>previously
                                    undisclosed</em> second verse.
                                </li>
                                <li>The style and essence of the updated Song should <em>not significantly
                                    differ</em> from the previous Song.
                                </li>
                                <li>With the aforementioned exceptions, Acts have the freedom to reinterpret, remix, or
                                    creatively evolve their original submission while maintaining its core essence.
                                </li>
                                <li>There is <em>no maximum duration</em> for the updated Song.</li>
                            </ul>

                            <HeadingSmall title="Voting"/>
                            <p className="mb-3">The conditions are the same as with the Knockout Stage, except:</p>
                            <ul className="list-disc my-3 mx-8">
                                <li>Visitors will be asked to choose their top three Songs among all entries.</li>
                            </ul>

                            <HeadingSmall title="Winning Songs"/>
                            <p className="mb-3">One overall winning Song and the three highest-scoring
                                runner-up Songs will be chosen.</p>

                            <ul className="list-disc my-3 mx-8">
                                <li>All winning Acts will be immortalised as 3D-printed figures.</li>
                                <li>The winning Song will be recognised as the <em>official anthem of
                                    the Contest</em>, and the Act associated
                                    with the winning Song will be immortalised as a <em>custom LEGO minifigure</em>.
                                </li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('golden-buzzer')}
                                 onOpenChange={() => toggleHandler('golden-buzzer')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Golden Buzzer
                            {isOpen('golden-buzzer') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <p className="mb-3">At any point during the Contest, Visitors can choose to support their
                                favourite Song by awarding a <b>Golden Buzzer</b>.</p>
                            <p className="mb-3">Golden Buzzers can be awarded to Songs through making a donation. The
                                simplest way to donate is through the respective PayPal button, or by contacting us
                                through this site for alternative methods.</p>
                            <p className="mb-3">A Song that receives a Golden Buzzer will receive the same treatment as
                                a <em>runner-up</em> of the respective Stage, but will not affect the Song's total
                                score.</p>

                            <HeadingSmall title="Golden Buzzer outcomes"/>
                            <ul className="list-disc mx-8 my-3">
                                <li>
                                    <b>During the Knockout Stage:</b> the associated Act will receive an updated
                                    profile and picture, along with an extended version of the Song.
                                </li>
                                <li>
                                    <b>During the Final:</b> the associated Act will be immortalised as a 3D-printed
                                    figure.
                                </li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('calculation')}
                                 onOpenChange={() => toggleHandler('calculation')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            How Winning Songs are Calculated
                            {isOpen('calculation') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <p className="mb-3">Scores for each Song are calculated from a total of the following:</p>
                            <ul className="list-disc mb-3 mx-8">
                                <li>4 points for each first choice vote;</li>
                                <li>2 points for each second choice vote;</li>
                                <li>1 point for each third choice vote.</li>
                            </ul>
                            <p className="mb-3">The winning Song in each Round is determined by the highest overall
                                score.</p>
                            <p className="text-sm italic mb-3">NOTE: Golden Buzzers do not count toward overall
                                scores.</p>

                            <HeadingSmall title="Tied votes"/>
                            <p className="mb-3">Songs in each Round are ranked in descending order of:</p>
                            <ul className="list-disc mb-3 mx-8">
                                <li>total score;</li>
                                <li>number of first choice votes;</li>
                                <li>number of second choice votes;</li>
                                <li>number of third choice votes.</li>
                            </ul>
                            <p className="mb-3">In the <em>very unlikely</em> event of a tie, the tied Songs will share
                                the rank. Therefore, it would be possible to have:</p>
                            <ul className="list-disc mb-3 mx-8">
                                <li>more than ten entries in the Final Stage;</li>
                                <li>more than one overall Winning Song;</li>
                                <li>more than three overall runner-up Songs.</li>
                            </ul>

                            <HeadingSmall title="No votes"/>
                            <p className="mb-3">In the event of <em>no votes</em> being cast for a specific Round, the
                                deciding
                                vote will be
                                determined by an independent panel.</p>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('advice')}
                                 onOpenChange={() => toggleHandler('advice')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Advice for Visitors
                            {isOpen('advice') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <ul className="list-disc mb-3 mx-8">
                                <li>We strongly encourage Visitors to vote for <em>the Songs they like the most</em>,
                                    and not <em>who they think is going to win</em>.
                                </li>
                                <li>Definitely consider supporting your most favourite Songs and the Contest, by
                                    awarding a <b>Golden Buzzer</b>.
                                </li>
                                <li>Help increase the excitement surrounding the Contest by sharing links to your
                                    favourite Songs, and <em>encourage others to vote</em> in the Contest!
                                </li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>
                </FrontContent>
                <FrontFooter/>
            </div>

        </>
    )
}
