import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import HeadingSmall from '@/components/heading-small';
import FrontLayout from '@/layouts/front-layout';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Advert } from '@/components/advert';
import { AboutSongPanel } from '@/pages/front/about/about-song';
import { AboutFoldPanel } from '@/pages/front/about/about-fold';
import { AboutSilentmodePanel } from '@/pages/front/about/about-silentmode';
import { AboutCatawolPanel } from '@/pages/front/about/about-catawol';

const AboutPage: React.FC = () => {

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

                <Advert className="mx-auto my-3 h-[60px] md:h-[90px] text-center"/>

                <AboutCatawolPanel/>
                <AboutSongPanel/>
                <AboutFoldPanel/>
                <AboutSilentmodePanel/>

                <Advert className="mx-auto my-3 h-[60px] md:h-[90px] text-center"/>
            </FrontContent>
        </>
    )
};

AboutPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default AboutPage;
