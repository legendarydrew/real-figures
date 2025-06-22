import { Head } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import HeadingSmall from '@/components/heading-small';
import ContestHeader from '@/components/front/contest-header';
import { Advert } from '@/components/mode/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';
import { PreviousRound } from '@/components/mode/previous-round';

const HomeStageEndPage: React.FC = ({ stage, previousRounds, isLastStage }) => {

    return (
        <>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-zinc-800 text-gray-100 py-10 px-5">
                <div className="max-w-5xl mx-auto">

                    <div className="flex flex-col lg:flex-row gap-5">

                        <div className="lg:w-2/3 text-center">
                            {isLastStage ? (
                                <>
                                    <h1 className="display-text text-2xl">The Finals Have Ended...</h1>
                                    <p className="my-3 text-lg">
                                        The stage has gone quiet. The last notes have been sung.
                                    </p>
                                    <p className="my-3">
                                        Now, it’s up to the numbers &mdash; and the anticipation is <i>electric</i>.
                                    </p>
                                    <p className="my-3">All our finalists are legends in their own right.</p>
                                    <p className="my-3 font-semibold">Only one will be immortalised as the official
                                        anthem.</p>
                                    <p className="my-3">
                                        We're counting the final votes. <b>The winner is coming.</b>
                                    </p>
                                </>
                            ) : (
                                <>
                                    <h1 className="display-text text-2xl">The Votes Are In...</h1>
                                    <p className="my-3 text-lg">
                                        <b>{stage.title} has ended, and the votes are being counted.</b> Thank you to
                                        everybody
                                        who took part in the voting!
                                    </p>
                                    <p className="my-3">
                                        <b>Who will make it to the next round? Who will just miss the cut?</b>
                                    </p>
                                    <p className="my-3">All will be revealed soon, so stay tuned.</p>
                                </>
                            )}
                        </div>

                        <div className="lg:w-1/3">
                            <Advert className="mx-auto text-center" height={90}/>
                        </div>
                    </div>

                    <div className="mt-5">
                        { !isLastStage ? (
                            <>
                                <HeadingSmall title={`Previous ${previousRounds.count === 1 ? 'Round' : 'Rounds'}`}/>
                                {stage.description && (
                                    <div className="content my-3 text-sm text-gray-200"
                                         dangerouslySetInnerHTML={{ __html: stage.description }}/>
                                )}
                            </>
                        ) : ''}
                        {previousRounds.map((round) => <PreviousRound key={round.id} round={round}/>)}
                    </div>

                </div>
            </div>

            <ContestHeader/>
            <Advert className="mx-auto text-center" height={240}/>
            <ContestOutline/>
            <GoldenBuzzerBanner/>
            <AboutBanner/>
        </>
    )
        ;
};

HomeStageEndPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeStageEndPage;
