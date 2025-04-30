import { Head } from '@inertiajs/react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import FrontLayout from '@/layouts/front-layout';

export default function HomeContestOver() {

    return (
        <FrontLayout>
            <Head title="The Contest Is Over.">
                {/* Any tags to go in the <head> section to here. */}
            </Head>

            <div className="bg-indigo-200 py-10 px-5">
                <div className="max-w-5xl mx-auto flex items-center gap-4">

                    <PlaceholderPattern className="stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <div className="w-1/2 ml-10">
                        <p className="text-base w-3/4">
                            Thank you for your support!
                        </p>
                    </div>

                </div>
            </div>
        </FrontLayout>
    );
}
