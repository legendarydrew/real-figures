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
import { CurrentRound } from '@/components/current-round';
import { CurrentStage } from '@/components/current-stage';

const HomeCurrentRoundPage: React.FC = ({ stage, currentRound, previousRounds, countdown }) => {

    return (
        <>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-indigo-950 text-white py-10 px-5">
                <div className="max-w-5xl mx-auto">
                    {currentRound && (
                        <>
                            <CurrentStage stage={stage} round={currentRound} countdown={countdown}/>
                            <CurrentRound round={currentRound}/>
                        </>
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

HomeCurrentRoundPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeCurrentRoundPage;
