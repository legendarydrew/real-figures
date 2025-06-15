import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/advert';
import AboutBanner from '@/components/front/about-banner';
import { RoundBreakdown } from '@/components/round-breakdown';

const VotesPage: React.FC = ({ stages }) => {

    return (
        <>
            <Head title="Breakdown of votes"/>

            <div className="bg-zinc-500 text-white">
                <FrontContent>
                    <h1 className="display-text text-2xl mb-5 text-center">Breakdown of votes</h1>

                    <div className="grid gap-5 lg:grid-cols-4">

                        <div className="col-span-3 flex flex-col gap-3">

                            {stages.map((stage) => (
                                <section key={stage.id}>
                                    <header className="bg-zinc-700 text-center p-2">
                                        <h2 className="display-text text-lg">{stage.title}</h2>
                                    </header>
                                    {stage.breakdowns.map((breakdown) => (
                                        <RoundBreakdown key={breakdown.id} className="mb-3" breakdown={breakdown}/>))}
                                </section>
                            ))}
                        </div>
                        <Advert className="col-span-1"/>
                    </div>

                </FrontContent>
            </div>

            <AboutBanner/>
            <Advert className="mx-auto my-3 text-center" height={90}/>
        </>
    );
};

VotesPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default VotesPage;
