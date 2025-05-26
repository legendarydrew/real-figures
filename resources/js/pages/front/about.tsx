import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import HeadingSmall from '@/components/heading-small';
import { useState } from 'react';
import { ChevronDown, ChevronUp } from 'lucide-react';
import FrontLayout from '@/layouts/front-layout';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Advert } from '@/components/advert';

const AboutPage: React.FC = () => {

    const [panelState, setPanelState] = useState({});

    const toggleHandler = (section: string): void => {
        setPanelState({ ...panelState, [section]: !panelState[section] });
    };

    const isOpen = (section: string): boolean => {
        return panelState[section] ?? false;
    }

    return (
        <>
            <Head title="About the Contest"/>

            <FrontContent>
                <Heading title="Real Figures Don't F.O.L.D &ndash; About the Project"/>

                <div className="content pt-3 pb-5 lg:pb-10 lg:px-2 flex flex-col md:flex-row gap-5 lg:gap-10">
                    <PlaceholderPattern className="md:w-2/5 stroke-neutral-900/20"/>
                    <div className="content md:w-3/5">
                        <p className="text-lg">
                            <b>Real Figures Don't F.O.L.D combines SilentMode's interest in LEGO</b> with music,
                            "artificial intelligence", web development and advocacy.
                        </p>
                        <p>This project serves many purposes:</p>

                        <ul className="list-disc">
                            <li>
                                <HeadingSmall title="Revisiting one of SilentMode's earliest Creations."/>
                                <p className="mb-3">CATAWOL Records began life as a modular building, designed and built
                                    near the beginning of SilentMode's time in the LEGO hobby. The model was designed
                                    without ever having owned or built an official modular building set.</p>
                            </li>
                            <li>
                                <HeadingSmall title="Embarking on an ambitious LEGO project."/>
                                <p className="mb-3">Expanding on his existing skills as a Maker, Artist and LEGO
                                    Enthusiast, this is SilentMode's first project to fully incorporate music, as well
                                    as AI/computer-generated content.</p>
                            </li>
                            <li>
                                <HeadingSmall
                                    title="Creating the first ever anti-bullying campaign (that we know of) within the LEGO space."/>
                                <p className="mb-3">An opportunity for SilentMode to highlight an important issue,
                                    which affects <em>both children and adults</em>, that has never been
                                    addressed before in the context of LEGO.</p>
                            </li>
                            <li>
                                <HeadingSmall title="A live demonstration of coding ability."/>
                                <p className="mb-3">This site was designed and built by SilentMode himself, using
                                    Laravel and Inertia for the back end, and React with Tailwind for the front end.
                                    Hopefully it will help him land his next role.</p>
                            </li>
                        </ul>

                    </div>
                </div>

                <div className="mx-auto">
                    <Advert/>
                </div>

                <Collapsible className="border-b" open={isOpen('about-label')}
                             onOpenChange={() => toggleHandler('about-label')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <span className="display-text flex-grow text-left">About CATAWOL Records</span>
                        {isOpen('about-label') ? <ChevronUp/> : <ChevronDown/>}
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

                <Collapsible className="border-b" open={isOpen('about-song')}
                             onOpenChange={() => toggleHandler('about-song')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <span className="display-text flex-grow text-left">About the Song</span>
                        {isOpen('about-song') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent
                        className="content pt-3 pb-5 lg:pb-10 px-2 lg:px-5 flex flex-col md:flex-row gap-5 lg:gap-10">
                        <PlaceholderPattern className="md:w-2/5 stroke-neutral-900/20"/>
                        <div className="md:w-3/5">
                            <p>
                                <b className="text-current">Real Figures Don't F.O.L.D</b> is a song <b
                                className="text-current">to those who have experienced
                                bullying</b>.</p>
                            <div className="text-sm">
                                <p>It was written from the perspective that <b>neither the bullies nor their enablers
                                    are
                                    listening or will listen</b>, and we can't expect them to change their ways.</p>
                                <p>There are <b>three meanings</b> behind the song:</p>
                                <ul className="list-disc mb-3 mx-5 flex flex-col gap-3">
                                    <li>Encouraging targets of bullying to <b>be authentic
                                        and true to themselves</b> (protecting the "gold" within them).
                                    </li>
                                    <li>That "selling out" to become part of the "in" crowd (the F.O.L.D) is <b
                                        className="text-muted-foreground">not an answer</b> ("you'll
                                        never get a good enough price for your soul").
                                    </li>
                                    <li>It also references SilentMode's minifigure redesign (MISMI), in which
                                        the figure stands upright and does not bend at the hip.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible className="border-b" open={isOpen('about-fold')}
                             onOpenChange={() => toggleHandler('about-fold')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <span className="display-text flex-grow text-left">What is the F.O.L.D?</span>
                        {isOpen('about-fold') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent
                        className="content pt-3 pb-5 lg:pb-10 px-2 lg:px-5 flex flex-col md:flex-row gap-5 lg:gap-10">
                        <PlaceholderPattern className="md:w-2/5 stroke-neutral-900/20"/>
                        <div className="md:w-3/5">
                            <p>The F.O.L.D is a nebulous, highly-connected network of fans and
                                employees operating within the LEGO space, often referring to itself as a
                                "community".</p>
                            <div className="text-sm">
                                <p>In the public eye, members of the F.O.L.D appear to be welcoming,
                                    friendly and inclusive. But with near absolute control over the hobby, it practices
                                    discrimination, exclusion and isolation, promoting specific agendas
                                    and elevating a small handful of people.</p>
                                <p>The concept of the F.O.L.D was introduced with SilentMode's <b>SCREAMix</b> project,
                                    first displayed at the 2024 Great Western Brick Show.</p>
                            </div>
                        </div>
                    </CollapsibleContent>
                </Collapsible>

                <Collapsible open={isOpen('about-silentmode')}
                             onOpenChange={() => toggleHandler('about-silentmode')}>
                    <CollapsibleTrigger
                        className="w-full flex justify-between items-center gap-3 text-lg p-3 cursor-pointer">
                        <span className="display-text flex-grow text-left">About SilentMode</span>
                        {isOpen('about-silentmode') ? <ChevronUp/> : <ChevronDown/>}
                    </CollapsibleTrigger>
                    <CollapsibleContent
                        className="content pt-3 pb-5 lg:pb-10 px-2 lg:px-5 flex flex-col md:flex-row gap-5 lg:gap-10">
                        <PlaceholderPattern className="md:w-2/5 stroke-neutral-900/20"/>
                        <div className="md:w-3/5">
                            <p>SilentMode is a Maker, Artist and LEGO Enthusiast based in London,
                                UK. He has been active within the LEGO space since 2010.</p>
                            <div className="text-sm">
                                <p>As well as designing and building his own models, he incorporates 3D printing,
                                    coding and graphic design into his interest in the hobby.</p>
                                <p>He is passionate about addressing bullying, mental health and men's
                                    issues &ndash; as a survivor.</p>
                            </div>
                        </div>
                    </CollapsibleContent>
                </Collapsible>
            </FrontContent>
        </>
    )
};

AboutPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default AboutPage;
