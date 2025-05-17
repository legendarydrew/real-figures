import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import HeadingSmall from '@/components/heading-small';
import { useState } from 'react';
import {
    Calculator,
    CheckCircle,
    ChevronDown,
    ChevronUp,
    GitFork,
    ListChecks,
    Notebook,
    ShieldQuestion,
    Star
} from 'lucide-react';
import FrontLayout from '@/layouts/front-layout';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';

const RulesPage: React.FC = () => {

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

            <FrontContent>
                <Heading title="Real Figures Don't F.O.L.D &ndash; Contest Rules"/>

                <div className="flex flex-col-reverse md:flex-row mb-5 gap-5 mb:gap-10">
                    <div className="md:w-3/5 content">
                        <p className="text-lg">
                            Welcome to the first-ever CATAWOL Records Song Contest!
                        </p>
                        <p>
                            We’re bringing together 32 of our biggest Acts to showcase incredible creativity and raise
                            awareness of bullying in adult spaces.
                        </p>
                        <p>
                            And we need <b>your votes</b> to help decide which song becomes the <b>official anthem!</b>
                        </p>
                    </div>
                    <div className="md:w-2/5">
                        <PlaceholderPattern className="w-full stroke-neutral-900/20 dark:stroke-neutral-100/20"/>
                    </div>
                </div>

                <Collapsible className="border-b" open={isOpen('brief')}
                             onOpenChange={() => toggleHandler('brief')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <Notebook/>
                        <span className="display-text flex-grow text-left">Contest Brief</span>
                        {isOpen('brief') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <p>Create an original song using lyrics written by the MODE Family’s <b>Sigfig</b>.</p>
                        <p>The goal: <b>raising awareness of bullying in adult environments</b> through music.</p>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('eligibility')}
                             onOpenChange={() => toggleHandler('eligibility')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <CheckCircle/>
                        <span className="display-text flex-grow text-left">Eligibility</span>
                        {isOpen('eligibility') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <ul>
                            <li>Entry is open to all Acts currently signed with <b>CATAWOL Records</b>.</li>
                            <li>Acts must remain signed with CATAWOL Records throughout the Contest.</li>
                            <li>32 Acts will be shortlisted to participate.</li>
                        </ul>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('criteria')}
                             onOpenChange={() => toggleHandler('criteria')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <ListChecks/>
                        <span className="display-text flex-grow text-left">Song Criteria</span>
                        {isOpen('criteria') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <ul>
                            <li>Songs must respect the original lyrics (minor adjustments allowed for flow or
                                grammar).
                            </li>
                            <li>Songs must feature vocals by the associated Act.</li>
                            <li>Each Act may submit <b>one</b> Song.</li>
                        </ul>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('knockout-stage')}
                             onOpenChange={() => toggleHandler('knockout-stage')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <GitFork/>
                        <span className="display-text flex-grow text-left">Stage 1: Knockout Stage</span>
                        {isOpen('knockout-stage') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <HeadingSmall title="Submissions"/>
                        <ul>
                            <li>Each Act submits a Song using the provided lyrics.</li>
                            <li><b>Song length</b> must not exceed <b>2 minutes and 10 seconds</b> (2:10).</li>
                            <li>Minor adjustments to lyrics are allowed, but the essence must remain intact.</li>
                            <li>Songs are randomly assigned into <b>8 Rounds</b>, with <b>4 Songs</b> per Round.</li>
                        </ul>

                        <HeadingSmall title="Voting"/>
                        <ul>
                            <li><b>Voting is open to all Visitors</b>, except:
                                <ul>
                                    <li>Members of the F.O.L.D.</li>
                                    <li>Relatives or affiliates of competing Acts.</li>
                                    <li>Contest administrators.</li>
                                    <li>Employees of CATAWOL Records.</li>
                                </ul>
                            </li>
                            <li>In each Round, Visitors can vote for their <b>top
                                3 favourite Songs.</b></li>
                            <li>Votes are only counted while a Round is open.</li>
                        </ul>

                        <HeadingSmall title="Advancement"/>
                        <p>A total of 10 Songs will advance to Stage 2:</p>
                        <ul>
                            <li>8 Round Winners (one from each Round).</li>
                            <li>2 highest-scoring runners-up across all Rounds.</li>
                        </ul>

                        <HeadingSmall title="Bonus for advancing Acts"/>
                        <p>Each Act will receive a newly crafted profile and an updated picture!</p>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('final-stage')}
                             onOpenChange={() => toggleHandler('final-stage')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <GitFork/>
                        <span className="display-text flex-grow text-left">Stage 2: Finals</span>
                        {isOpen('final-stage') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <HeadingSmall title="Resubmission"/>
                        <ul>
                            <li>Acts must submit an <b>updated version</b> of their Song.</li>
                            <li>Songs must now include a <b>previously undisclosed second verse.</b></li>
                            <li>The updated Song must <b>retain the original style and essence</b> while allowing for
                                creative reinterpretation.
                            </li>
                            <li><b>No maximum duration</b> for the updated Song.</li>
                        </ul>

                        <HeadingSmall title="Final Voting"/>
                        <p>Voting conditions are the same as Stage 1, except:</p>
                        <ul>
                            <li>Visitors will vote for their <b>top 3 favourites</b> from <b>all</b> entries combined.
                            </li>
                        </ul>

                        <HeadingSmall title="Winning Songs"/>
                        <ul>
                            <li>One <b>Grand Winner</b> and three <b>Runners-up</b> will be crowned.</li>
                        </ul>

                        <HeadingSmall title="Prizes"/>
                        <ul>
                            <li>All winning Acts will be recreated as <b>3D-printed figures</b>.</li>
                            <li>The Act behind the Grand Winning Song will also be honoured with a <b>custom LEGO
                                minifigure.</b></li>
                            <li>The Winning Song becomes the <b>official anthem</b> of the Contest!</li>
                        </ul>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('golden-buzzer')}
                             onOpenChange={() => toggleHandler('golden-buzzer')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <Star className="text-yellow-500"/>
                        <span className="display-text flex-grow text-left">The Golden Buzzer</span>
                        {isOpen('golden-buzzer') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <p>Want to give a Song an extra boost? Hit the <b className="text-yellow-500">Golden
                            Buzzer</b>!</p>
                        <ul>
                            <li>Visitors can award a Golden Buzzer to their favourite Song by donating.</li>
                            <li>Donations can be made through the provided PayPal button or by contacting us for
                                alternative methods.
                            </li>
                            <li>A Golden Buzzer means:
                                <ul>
                                    <li><b>Knockout Stage:</b> The Act gets a backstory, an updated picture, and an
                                        extended version of their Song.
                                    </li>
                                    <li><b>Final Stage:</b> The Act is immortalised as a <b>3D-printed figure</b>.</li>
                                </ul>
                            </li>
                        </ul>
                        <p><b>Important:</b> Golden Buzzers <b>do not</b> affect voting scores - they’re a bonus honour!
                        </p>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('calculation')}
                             onOpenChange={() => toggleHandler('calculation')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <Calculator/>
                        <span className="display-text flex-grow text-left">How Votes Are Calculated</span>
                        {isOpen('calculation') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <ul>
                            <li>1st choice vote = <b>4 points</b></li>
                            <li>2nd choice vote = <b>2 points</b></li>
                            <li>3rd choice vote = <b>1 point</b></li>
                        </ul>

                        <p>Songs are ranked based on:</p>
                        <ol>
                            <li>Total score</li>
                            <li>Number of 1st choice votes</li>
                            <li>Number of 2nd choice votes</li>
                            <li>Number of 3rd choice votes</li>
                        </ol>

                        <p className="italic">Note: Golden Buzzers are honorary and do not influence scores.</p>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('situations')}
                             onOpenChange={() => toggleHandler('situations')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <ShieldQuestion/>
                        <span className="display-text flex-grow text-left">Special Situations</span>
                        {isOpen('situations') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">

                        <HeadingSmall title="Tied votes"/>
                        <p>In the rare event of a tie:</p>
                        <ul>
                            <li>More than 10 Acts may advance to the Finals.</li>
                            <li>More than one Grand Winner or additional Runners-up may be declared.</li>
                        </ul>

                        <HeadingSmall title="No votes"/>
                        <p>If no votes are cast in a Round:</p>
                        <ul>
                            <li>Winners will be decided by an independent panel.</li>
                        </ul>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible open={isOpen('advice')}
                             onOpenChange={() => toggleHandler('advice')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center text-lg p-3 cursor-pointer">
                        <span className="display-text">Advice for Visitors</span>
                        {isOpen('advice') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="content pb-5 px-2 md:px-5">
                        <ul>
                            <li><b>Vote for the Songs you love the most</b>, not just the ones you think will win.</li>
                            <li><b>Support your favourites with a Golden Buzzer</b> or by donating to help the Contest
                                grow.
                            </li>
                            <li><b>Spread the word!</b> Share your favourite Songs and encourage friends to vote.</li>
                        </ul>
                        <p>Let's make music history together!</p>
                    </CollapsibleContent>
                </Collapsible>
            </FrontContent>
        </>
    )
}

RulesPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default RulesPage;
