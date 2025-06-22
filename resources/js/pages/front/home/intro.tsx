import { Head } from '@inertiajs/react';
import FrontLayout from '@/layouts/front-layout';
import ContestHeader from '@/components/front/contest-header';
import { Advert } from '@/components/mode/advert';
import ContestOutline from '@/components/front/contest-outline';
import GoldenBuzzerBanner from '@/components/front/golden-buzzer-banner';
import AboutBanner from '@/components/front/about-banner';
import { SubscribeForm } from '@/components/front/subscribe-form';

const IntroPage: React.FC = () => {

    return (
        <>
            <Head>
                {/* Any tags to go in the <head> section to here. */}
                <meta name="description" content="32 Acts. 1 anthem. Vote for your favourite AI-generated song, in the world's first ever LEGO-related anti-bullying campaign."/>
            </Head>

            <ContestHeader>
                <p><b>Be the first to hear when voting opens, songs drop and surprises land.</b> Subscribe below to get
                    updates straight to your inbox.
                </p>
                <SubscribeForm className="mt-3"/>
            </ContestHeader>
            <Advert className="mx-auto text-center" height={90}/>
            <ContestOutline/>
            <GoldenBuzzerBanner/>
            <AboutBanner/>
        </>
    );
};

IntroPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default IntroPage;
