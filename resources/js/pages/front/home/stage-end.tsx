import { Head } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import Heading from '@/components/heading';
import HeadingSmall from '@/components/heading-small';
import { ActImage } from '@/components/ui/act-image';
import ContestHeader from '@/components/front/contest-header';
import { Advert } from '@/components/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';

const HomeStageEndPage: React.FC = ({ stage, previousRounds }) => {

    return (
        <>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-blue-100 py-10 px-5">
                <div className="max-w-5xl mx-auto">

                    <h1 className="display-text text-2xl">Voting has ended for the current Stage.</h1>
                    <p className="my-5">Thank you to everybody who took part in voting! Stay tuned for the results!</p>

                    <Heading title={stage.title}/>
                    {stage.description && (
                        <div className="content my-3 text-sm text-muted-foreground"
                             dangerouslySetInnerHTML={{ __html: stage.description }}/>
                    )}

                    {previousRounds.length ? (
                        <>
                            <Heading title="Previous Rounds"/>
                            {previousRounds.map((round) => (
                                <div key={round.id} className="mb-2">
                                    <HeadingSmall title={round.title}/>
                                    <ul className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                        {round.songs.map((song) => (
                                            <li className="bg-secondary/15 rounded-md leading-none relative"
                                                key={song.id}>
                                                <ActImage act={song.act} size="full"/>
                                                <div className="p-5 absolute bottom-0">
                                                    <div className="text-base font-semibold">{song.act.name}</div>
                                                    <div className="text-sm font-semibold">{song.title}</div>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            ))}
                        </>
                    ) : ''}
                </div>
            </div>

            <ContestHeader/>
            <Advert className="mx-auto max-h-[280px] md:max-h-[240px] text-center"/>
            <ContestOutline/>
            <GoldenBuzzerBanner/>
            <AboutBanner/>
        </>
    );
};

HomeStageEndPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeStageEndPage;
