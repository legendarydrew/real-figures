import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import HeadingSmall from '@/components/heading-small';
import { useState } from 'react';
import { ChevronDown, ChevronUp } from 'lucide-react';
import FrontLayout from '@/layouts/front-layout';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';

export default function AboutPage() {

    const [panelState, setPanelState] = useState({});

    const toggleHandler = (section: string): void => {
        setPanelState({ ...panelState, [section]: !panelState[section] });
    };

    const isOpen = (section: string): boolean => {
        return panelState[section] ?? false;
    }

    return (
        <FrontLayout>
            <Head title="About the Contest"/>

            <FrontContent>
                <Heading title="Real Figures Don't F.O.L.D &ndash; About the Project"/>


                <Collapsible className="border-b" open={isOpen('about-label')}
                             onOpenChange={() => toggleHandler('about-label')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg font-semibold p-3 cursor-pointer">
                        <span className="flex-grow text-left">About CATAWOL Records</span>
                        {isOpen('about-label') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="pt-3 pb-10 px-5 flex gap-10">
                        <PlaceholderPattern className="w-2/5 stroke-neutral-900/20"/>
                        <div className="w-3/5">
                            <p className="mb-3">
                                <b>CATAWOL Records is a fictitious music label,</b> created as the subject of
                                SilentMode's first ever LEGO modular building, back in 2011.
                            </p>

                            <HeadingSmall title="Overview"/>
                            <p className="mb-3">
                                CATAWOL Records is a globally influential record label that has shaped the music
                                industry for over seventy years. Founded in 1953 by industry magnates Henry Cardinal,
                                Simon Tate, and Reginald Wolseley, the label has been both lauded for its altruism and
                                criticised for its commercial approach to talent development. CATAWOL has played a
                                pivotal role in launching and sustaining the careers of some of the biggest names in
                                music, though not without controversy.
                            </p>

                            <HeadingSmall title="History"/>
                            <p className="mb-3">
                                In its early years, CATAWOL Records was instrumental in introducing groundbreaking
                                artists to the public. Originally conceived as an independent label with an artist-first
                                approach, it quickly gained traction for its ability to recognise and amplify emerging
                                talent. By the late 1960s, CATAWOL had established itself as a dominant force in the
                                music business, acquiring smaller labels and solidifying its influence across multiple
                                genres.
                            </p>
                            <p className="mb-3">
                                During the 1980s and 1990s, the label fully embraced the concept of manufactured
                                celebrities, investing heavily in market-driven talent scouting, production, and
                                branding. This approach allowed CATAWOL to maintain its commercial dominance, but it
                                also drew criticism from purists who accused the company of prioritising profitability
                                over artistic integrity.
                            </p>
                            <p className="mb-3">
                                Today, only one of the founder's families remains directly involved in the label,
                                maintaining a significant role in its operations and strategic direction.
                            </p>

                            <HeadingSmall title="Business Model and Influence"/>
                            <p className="mb-3">
                                CATAWOL Records operates as a multi-tiered entertainment conglomerate, encompassing
                                artist management, production, publishing, and merchandising. The label has been
                                known for its strategic partnerships with major media corporations, ensuring
                                widespread promotion for its artists.
                            </p>
                            <p className="mb-3">
                                Despite its calculated commercial approach, CATAWOL has also engaged in
                                philanthropic initiatives, funding music education programs and supporting
                                independent artists through subsidiary labels. This duality &ndash; balancing
                                profit-driven
                                operations with charitable endeavours &ndash; has led to the label being described as a
                                "necessary evil" within the industry.
                            </p>
                        </div>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('about-song')}
                             onOpenChange={() => toggleHandler('about-song')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg font-semibold p-3 cursor-pointer">
                        <span className="flex-grow text-left">About the Song</span>
                        {isOpen('about-song') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="pt-3 pb-10 px-5 flex gap-10">
                        <PlaceholderPattern className="w-2/5 stroke-neutral-900/20"/>
                        <div className="w-3/5">
                            <p className="mb-3"><b>Real Figures Don't F.O.L.D</b> is a song <b
                                className="text-muted-foreground">to those who have experienced
                                bullying</b>.</p>
                            <p className="mb-3">Inspired by a line from <b className="text-muted-foreground">M.O.P's
                                Cold As Ice</b>, it was written from the perspective
                                that <b className="text-muted-foreground">neither the bullies nor their enablers are
                                    listening</b>, and we can't expect them to change their ways.</p>
                            <p className="mb-3">There are actually <b className="text-muted-foreground">three
                                meanings</b> behind the song:</p>
                            <ul className="list-disc mb-3 mx-5 flex flex-col gap-3">
                                <li>Encouraging targets of bullying to <b className="text-muted-foreground">be authentic
                                    and true to
                                    themselves</b> (the "gold"
                                    within them).
                                </li>
                                <li>That "selling out" to become part of the "in" crowd (the F.O.L.D) is <b
                                    className="text-muted-foreground">not an answer</b>, because "you'll
                                    never get a good enough price for your soul".
                                </li>
                                <li>It is also a reference to SilentMode's LEGO minifigure redesign (MISMI), in which
                                    the figure stands upright and does not bend at the hip.
                                </li>
                            </ul>
                        </div>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('about-fold')}
                             onOpenChange={() => toggleHandler('about-fold')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg font-semibold p-3 cursor-pointer">
                        <span className="flex-grow text-left">What is the F.O.L.D?</span>
                        {isOpen('about-fold') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="pt-3 pb-10 px-5 flex gap-10">
                        <PlaceholderPattern className="w-2/5 stroke-neutral-900/20"/>
                        <div className="w-3/5">
                            <p className="mb-3">The F.O.L.D is a nebulous, highly-connected network of fans and
                                employees operating within the LEGO space, often referring to itself as a
                                "community".</p>
                            <p className="mb-3">In the public eye, members of the F.O.L.D appear to be welcoming,
                                friendly and inclusive. But with near absolute control over the hobby, it practices
                                discrimination, ostracism and isolation, in favour of promoting specific agendas
                                and elevating a small handful of people.</p>
                            <p className="mb-3">The concept of the F.O.L.D was publicly introduced in
                                SilentMode's <b className="text-muted-foreground">SCREAMix</b> project, first displayed
                                at the Great Western Brick Show
                                in 2024.</p>
                        </div>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible open={isOpen('about-silentmode')}
                             onOpenChange={() => toggleHandler('about-silentmode')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg font-semibold p-3 cursor-pointer">
                        <span className="flex-grow text-left">About SilentMode</span>
                        {isOpen('about-silentmode') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent className="pt-3 pb-10 px-5 flex gap-10">
                        <PlaceholderPattern className="w-2/5 stroke-neutral-900/20"/>
                        <div className="w-3/5">
                            <p className="mb-3">SilentMode is a Maker, Artist and LEGO Enthusiast based in London,
                                UK. He has been active within the LEGO space since 2010.</p>
                            <p className="mb-3">He is passionate about addressing bullying, mental health and men's
                                issues &ndash; as a survivor.</p>
                        </div>
                    </CollapsibleContent>
                </Collapsible>
            </FrontContent>
        </FrontLayout>
    )
}
