import { Head } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import HeadingSmall from '@/components/heading-small';
import ContestHeader from '@/components/front/contest-header';
import { Advert } from '@/components/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';
import { CurrentRound } from '@/components/current-round';
import { CurrentStage } from '@/components/current-stage';
import { PreviousRound } from '@/components/previous-round';

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
                        <div className="mt-5">
                            <HeadingSmall title="Previous Rounds"/>
                            {previousRounds.map((round) => <PreviousRound key={round.id} round={round}/>)}
                        </div>
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
