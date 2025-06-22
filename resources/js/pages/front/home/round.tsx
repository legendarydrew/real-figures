import { Head } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import HeadingSmall from '@/components/heading-small';
import ContestHeader from '@/components/front/contest-header';
import { Advert } from '@/components/mode/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';
import { CurrentRound } from '@/components/mode/current-round';
import { CurrentStage } from '@/components/mode/current-stage';
import { PreviousRound } from '@/components/mode/previous-round';

const HomeCurrentRoundPage: React.FC = ({ stage, currentRound, previousRounds, countdown }) => {

    return (
        <>
            <Head title={currentRound ? currentRound.full_title : stage.title}>
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

                    {(currentRound && previousRounds.length) ? (
                        <Advert className="mx-auto text-center" height={240}/>
                    ) : ''}

                    {previousRounds.length ? (
                        <div className="mt-5">
                            <HeadingSmall title={`Previous ${previousRounds.count === 1 ? 'Round' : 'Rounds'}`}/>
                            {previousRounds.map((round) => <PreviousRound key={round.id} round={round}/>)}
                        </div>
                    ) : ''}
                </div>
            </div>

            <ContestHeader/>
            <Advert className="mx-auto text-center" height={240}/>
            <ContestOutline/>
            <GoldenBuzzerBanner/>
            <AboutBanner/>
        </>
    );
};

HomeCurrentRoundPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeCurrentRoundPage;
