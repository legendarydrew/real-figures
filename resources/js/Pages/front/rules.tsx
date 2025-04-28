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
        setPanelState({ ...panelState, [section]: !panelState[section] });
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
                    <p className="text-lg my-3">
                        Welcome to the first-ever CATAWOL Records Song Contest!
                    </p>
                    <p className="text-lg my-3">
                        We’re bringing together 32 of our biggest Acts to showcase incredible creativity and raise
                        awareness of bullying in adult spaces.
                    </p>
                    <p className="text-lg my-3">
                        And we need <b className="text-muted-foreground">your votes</b> to help decide which song
                        becomes the <b className="text-muted-foreground">official
                        anthem!</b>
                    </p>

                    <Collapsible className="border-b" open={isOpen('brief')}
                                 onOpenChange={() => toggleHandler('brief')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Contest Brief
                            {isOpen('brief') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <p className="mb-3">Create an original song using lyrics written by the MODE Family’s <b
                                className="text-muted-foreground">Sigfig</b>.</p>
                            <p className="mb-3">The goal: <b className="text-muted-foreground">raise awareness of
                                bullying in adult
                                environments</b> through music.</p>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('eligibility')}
                                 onOpenChange={() => toggleHandler('eligibility')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Eligibility
                            {isOpen('eligibility') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <ul className="list-disc mb-3 mx-8">
                                <li>Entry is open to all Acts currently signed with <b
                                    className="text-muted-foreground">CATAWOL Records</b>.
                                </li>
                                <li>Acts must remain signed with CATAWOL Records throughout the Contest.</li>
                                <li>32 Acts will be shortlisted to participate.</li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('criteria')}
                                 onOpenChange={() => toggleHandler('criteria')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Song Criteria
                            {isOpen('criteria') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <ul className="list-disc mb-3 mx-8">
                                <li>Songs must respect the original lyrics (minor adjustments allowed for flow or
                                    grammar).
                                </li>
                                <li>Songs must feature vocals by the associated Act.</li>
                                <li>Each Act may submit <b className="text-muted-foreground">one</b> Song.</li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('knockout-stage')}
                                 onOpenChange={() => toggleHandler('knockout-stage')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Stage 1: Knockout Stage
                            {isOpen('knockout-stage') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <HeadingSmall title="Submissions"/>
                            <ul className="list-disc my-3 mx-8">
                                <li>Each Act submits a Song using the provided lyrics.</li>
                                <li><b className="text-muted-foreground">Song length</b> must not exceed <b
                                    className="text-muted-foreground">2
                                    minutes and 10 seconds</b> (2:10).
                                </li>
                                <li>Minor adjustments to lyrics are allowed, but the essence must remain intact.</li>
                                <li>Songs are randomly assigned into <b className="text-muted-foreground">8 Rounds</b>,
                                    with <b className="text-muted-foreground">4 Songs</b> per Round.
                                </li>
                            </ul>

                            <HeadingSmall title="Voting"/>
                            <ul className="list-disc my-3 mx-8">
                                <li><b className="text-muted-foreground">Voting is open to all Visitors</b>, except:
                                    <ul className="list-disc my-3 mx-8">
                                        <li>Members of the F.O.L.D.</li>
                                        <li>Relatives or affiliates of competing Acts.</li>
                                        <li>Contest administrators.</li>
                                        <li>Employees of CATAWOL Records.</li>
                                    </ul>
                                </li>
                                <li>In each Round, Visitors can vote for their <b className="text-muted-foreground">top
                                    3 favourite Songs.</b></li>
                                <li>Votes are only counted while a Round is open.</li>
                            </ul>

                            <HeadingSmall title="Advancement"/>
                            <p className="mb-3">A total of 10 Songs will advance to Stage 2:</p>
                            <ul className="list-disc my-3 mx-8">
                                <li>8 Round Winners (one from each Round).</li>
                                <li>2 highest-scoring runners-up across all Rounds.</li>
                            </ul>

                            <HeadingSmall title="Bonus for advancing Acts"/>
                            <p className="mb-3">Each Act will receive a newly crafted profile and an updated
                                picture!</p>
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
                            <ul className="list-disc my-3 mx-8">
                                <li>Acts must submit an <b className="text-muted-foreground">updated version</b> of
                                    their Song.
                                </li>
                                <li>Songs must now include a <b className="text-muted-foreground">previously undisclosed
                                    second verse.</b></li>
                                <li>The updated Song must <b className="text-muted-foreground">retain the original style
                                    and essence</b> while allowing for creative reinterpretation.
                                </li>
                                <li><b className="text-muted-foreground">No maximum duration</b> for the updated Song.
                                </li>
                            </ul>

                            <HeadingSmall title="Final Voting"/>
                            <p className="mb-3">Voting conditions are the same as Stage 1, except:</p>
                            <ul className="list-disc my-3 mx-8">
                                <li>Visitors will vote for their <b className="text-muted-foreground">top 3
                                    favourites</b> from <b className="text-muted-foreground">all</b> entries
                                    combined.
                                </li>
                            </ul>

                            <HeadingSmall title="Winning Songs"/>
                            <ul className="list-disc my-3 mx-8">
                                <li>One <b className="text-muted-foreground">Grand Winner</b> and three <b
                                    className="text-muted-foreground">Runners-up</b> will be crowned.
                                </li>
                            </ul>

                            <HeadingSmall title="Prizes"/>
                            <ul className="list-disc my-3 mx-8">
                                <li>All winning Acts will be recreated as <b className="text-muted-foreground">3D-printed
                                    figures</b>.
                                </li>
                                <li>The Act behind the Grand Winning Song will also be honoured with a <b
                                    className="text-muted-foreground">custom
                                    LEGO minifigure.</b></li>
                                <li>The Winning Song becomes the <b className="text-muted-foreground">official
                                    anthem</b> of the Contest!
                                </li>
                            </ul>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('golden-buzzer')}
                                 onOpenChange={() => toggleHandler('golden-buzzer')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            The Golden Buzzer
                            {isOpen('golden-buzzer') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <p className="mb-3">Want to give a Song an extra boost? Hit the <b>Golden Buzzer</b>!</p>
                            <ul className="list-disc my-3 mx-8">
                                <li>Visitors can award a Golden Buzzer to their favourite Song by donating.</li>
                                <li>Donations can be made through the provided PayPal button or by contacting us for
                                    alternative methods.
                                </li>
                                <li>A Golden Buzzer means:
                                    <ul className="list-disc my-3 mx-8">
                                        <li><b className="text-muted-foreground">Knockout Stage:</b> The Act gets a
                                            backstory, an updated picture, and an
                                            extended version of their Song.
                                        </li>
                                        <li><b className="text-muted-foreground">Final Stage:</b> The Act is
                                            immortalised as
                                            a <b className="text-muted-foreground">3D-printed figure</b>.
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <p className="mb-3"><b className="text-muted-foreground">Important:</b> Golden Buzzers <b
                                className="text-muted-foreground">do
                                not</b> affect voting scores - they’re a bonus honour!</p>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('calculation')}
                                 onOpenChange={() => toggleHandler('calculation')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            How Votes Are Calculated
                            {isOpen('calculation') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">
                            <ul className="list-disc mb-3 mx-8">
                                <li>1st choice vote = <b className="text-muted-foreground">4 points</b></li>
                                <li>2nd choice vote = <b className="text-muted-foreground">2 points</b></li>
                                <li>3rd choice vote = <b className="text-muted-foreground">1 point</b></li>
                            </ul>

                            <p className="mb-3">Songs are ranked based on:</p>
                            <ol className="list-decimal mb-3 mx-8">
                                <li>Total score</li>
                                <li>Number of 1st choice votes</li>
                                <li>Number of 2nd choice votes</li>
                                <li>Number of 3rd choice votes</li>
                            </ol>

                            <p className="italic mb-3">Note: Golden Buzzers are honorary and do not influence
                                scores.</p>
                        </CollapsibleContent>
                    </Collapsible>

                    <Collapsible className="border-b" open={isOpen('situations')}
                                 onOpenChange={() => toggleHandler('situations')}>
                        <CollapsibleTrigger
                            className="w-full flex justify-between items-center text-lg font-semibold p-3 cursor-pointer">
                            Special Situations
                            {isOpen('situations') ? <ChevronUp/> : <ChevronDown/>}
                        </CollapsibleTrigger>
                        <CollapsibleContent className="py-3 px-5">

                            <HeadingSmall title="Tied votes"/>
                            <p className="mb-3">In the rare event of a tie:</p>
                            <ul className="list-disc my-3 mx-8">
                                <li>More than 10 Acts may advance to the Finals.</li>
                                <li>More than one Grand Winner or additional Runners-up may be declared.</li>
                            </ul>

                            <HeadingSmall title="No votes"/>
                            <p className="mb-3">If no votes are cast in a Round:</p>
                            <ul className="list-disc my-3 mx-8">
                                <li>Winners will be decided by an independent panel.</li>
                            </ul>
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
                                <li><b className="text-muted-foreground">Vote for the Songs you love the most</b>, not
                                    just the ones you think will win..
                                </li>
                                <li><b className="text-muted-foreground">Support your favourites with a Golden
                                    Buzzer</b> or by donating to help the Contest grow.
                                </li>
                                <li><b className="text-muted-foreground">Spread the word!</b> Share your favourite Songs
                                    and encourage friends to vote.
                                </li>
                            </ul>
                            <p className="my-3">Let's make music history together!</p>
                        </CollapsibleContent>
                    </Collapsible>
                </FrontContent>
                <FrontFooter/>
            </div>

        </>
    )
}
