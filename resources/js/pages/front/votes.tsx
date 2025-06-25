import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/mode/advert';
import AboutBanner from '@/components/front/about-banner';
import { RoundBreakdown } from '@/components/mode/round-breakdown';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ChevronDown, ChevronUp } from 'lucide-react';
import { useState } from 'react';

const VotesPage: React.FC = ({ stages }) => {

    const [openPanels, setOpenPanels] = useState({ 0: true });

    const togglePanelHandler = (index): void => {
        setOpenPanels((prev) => ({ ...prev, [index]: !prev[index] }))
    };

    return (
        <>
            <Head title="Breakdown of votes">
                <meta name="description"
                      content="Explore the full results of the CATAWOL Records Song Contest. See how the votes were counted, which songs advanced, and how the winners were decided."/>
            </Head>

            <div className="bg-zinc-500 text-white">
                <FrontContent>
                    <h1 className="display-text text-2xl mb-5">Breakdown of votes</h1>

                    <div className="grid gap-5 lg:grid-cols-4">

                        <div className="col-span-3 flex flex-col gap-3">

                            {stages.map((stage, index) => (
                                <Collapsible key={stage.id} open={openPanels[index]}
                                             onOpenChange={() => togglePanelHandler(index)}>
                                    <CollapsibleTrigger
                                        className="w-full flex gap-2 items-center justify-center cursor-pointer bg-zinc-700 hover:bg-zinc-600 text-center p-2">
                                        <h2 className="display-text text-lg">{stage.title}</h2>
                                        {openPanels[index] ? <ChevronUp/> : <ChevronDown/>}
                                    </CollapsibleTrigger>
                                    <CollapsibleContent>
                                        {stage.breakdowns.map((breakdown) => (
                                            <RoundBreakdown key={breakdown.id} className="mb-3"
                                                            breakdown={breakdown}/>))}
                                    </CollapsibleContent>
                                </Collapsible>
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
