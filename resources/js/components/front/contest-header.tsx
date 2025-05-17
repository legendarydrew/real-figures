import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';

export default function ContestHeader() {
    return (
        <div className="bg-indigo-200 md:py-10 md:px-5">
            <div className="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-4">

                <PlaceholderPattern className="stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                <div className="px-3 md:px-0 md:w-1/2 md:ml-10">
                    <h1 className="display-text text-4xl text-shadow-md mb-3">
                        32 Acts.<br/>1 Anthem.
                    </h1>
                    <p className="text-base mb-3 md:w-3/4">
                        We're raising awareness about bullying through music - and <b>you</b> help pick the
                        winner!
                    </p>
                </div>

            </div>
        </div>
    )
}
