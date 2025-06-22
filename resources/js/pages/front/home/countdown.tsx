import { Head, router } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import { CountdownTimer } from '@/components/mode/countdown-timer';
import { Advert } from '@/components/mode/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';
import ContestHeader from '@/components/front/contest-header';

const HomeRound: React.FC = ({ stage, countdown }) => {

    const countdownEndHandler = () => {
        router.reload({
            fresh: true
        });
    }

    return (
        <>
            <Head title={`Countdown to ${stage.title}`}>
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-blue-100 py-10 px-5">
                <div className="max-w-5xl mx-auto text-center">

                    <h1 className="text-3xl display-text">Get Ready For {stage.title}!</h1>
                    {stage.description && (
                        <div className="content mt-3 mb-5 text-base text-muted-foreground"
                             dangerouslySetInnerHTML={{ __html: stage.description }}/>
                    )}
                    <div className="flex flex-col gap-1 items-center">
                        <CountdownTimer size="large" timestamp={countdown} onEnd={countdownEndHandler}/>
                        <span className="text-sm display-text">before voting begins!</span>
                    </div>

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

HomeRound.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default HomeRound;
