import { Head, Link } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Nothing } from '@/components/mode/nothing';
import { Donation } from '@/types';
import { cn } from '@/lib/utils';
import { Advert } from '@/components/mode/advert';
import { useDialog } from '@/context/dialog-context';
import { DONATE_DIALOG_NAME } from '@/components/front/donate-dialog';
import { Button } from '@/components/ui/button';

interface DonorWallPageProps {
    donations: Donation[];
    buzzers: Donation[];
}

const DonorWallPage: React.FC<DonorWallPageProps> = ({ donations, buzzers }) => {

    const { openDialog } = useDialog();

    const showDonateDialog = () => {
        openDialog(DONATE_DIALOG_NAME);
    };

    return (
        <>
            <Head title="Donor Wall">
                <meta name="description"
                      content="Support the CATAWOL Records Song Contest and help us amplify creativity and raise awareness of bullying. Learn how to donate and make a difference."/>
            </Head>

            <FrontContent>
                <div className="flex flex-col-reverse md:flex-row gap-5 mb-8">
                    <div className="content md:w-3/5">
                        <Heading title="Donor Wall"/>
                        <p>
                            <b>Consider supporting the contest by making a donation.</b>
                        </p>
                        <p>All the money raised will go toward:</p>
                        <ul className="list-disc">
                            <li>the costs associated with building and maintaining the site;</li>
                            <li>supporting our Acts by helping them make music;</li>
                            <li>supporting the MODE Family in their time of need.</li>
                        </ul>
                        <p>You can also <Link className="font-semibold hover:underline" href={route('contact')}>contact
                            us</Link> to suggest other ways of supporting the contest.</p>
                        <Button size="lg" className="bg-green-600 hover:bg-green-700 w-full lg:w-1/2" type="button"
                                onClick={showDonateDialog}>
                            Make a donation
                        </Button>
                    </div>

                    <figure className="md:w-2/5 overflow-hidden">
                        <img className="w-full" src="/img/chelsea-chapel.jpg"
                             alt="Classical singer Chelsea Chapel during a live performance."/>
                    </figure>
                </div>

                <div className="flex flex-col lg:flex-row gap-5">

                    <div className="w-full lg:w-2/3 rounded-md bg-green-100 dark:bg-green-700/50">
                        <h2 className="font-display bg-green-300 dark:bg-green-700/50 text-base px-3 py-1.5 rounded-t-md">Generous
                            Donations</h2>
                        {donations.length ? (
                            <>
                                <p className="px-3 py-2">
                                    <b>A huge thank you</b> to these generous people for donating to the project:
                                </p>
                                <ul className="text-sm flex flex-wrap overflow-y-auto max-h-[50dvh] px-3 py-2">
                                    {donations.map((donation) => (
                                        <li key={donation.id}
                                            className="w-full md:w-1/2 flex justify-between items-center gap-3 rounded-sm hover:bg-green-200 dark:hover:bg-green-200/20 px-2 py-0.5 select-none">
                                    <span
                                        className={cn("flex-grow truncate", donation.is_anonymous ? "italic" : "font-semibold")}>{donation.name}</span>
                                            <span className="text-xs text-right">{donation.created_at}</span>
                                        </li>
                                    ))}
                                </ul>
                            </>
                        ) : (
                            <Nothing className="text-sm p-5 leading-tight">Be the first to make a donation!</Nothing>
                        )}
                    </div>

                    <div className="w-full lg:w-1/3 rounded-md bg-amber-100 dark:bg-amber-700/50">
                        <h2 className="font-display bg-amber-300 dark:bg-amber-700/50 text-base px-3 py-1.5 rounded-t-md">Golden
                            Buzzers</h2>
                        {buzzers.length ? (
                            <ul className="text-sm flex flex-wrap overflow-y-auto max-h-[50dvh] px-3 py-2">
                                {buzzers.map((donation) => (
                                    <li key={donation.id}
                                        className="w-full flex justify-between items-center gap-3 rounded-sm hover:bg-amber-200 dark:hover:bg-amber-200/20 px-2 py-0.5 select-none">
                                        <span
                                            className={cn("flex-grow", donation.is_anonymous ? "italic" : "font-semibold")}>{donation.name}</span>
                                        <span className="text-xs text-right">{donation.created_at}</span>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <Nothing className="text-amber-900 dark:text-amber-100/80 p-5 text-sm leading-tight">Will
                                you be the first to hit
                                a Golden Buzzer?</Nothing>
                        )}
                    </div>

                </div>

                <Advert className="mx-auto my-3 text-center" height={90}/>

            </FrontContent>
        </>
    )
}

DonorWallPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default DonorWallPage;
