import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';

export default function ContestHeader() {
    return (
        <div className="bg-indigo-200 py-10 px-5">
            <div className="max-w-5xl mx-auto flex items-center gap-4">

                <PlaceholderPattern className="stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                <div className="w-1/2 ml-10">
                    <h1 className="display-text text-4xl text-shadow-md mb-3">
                        32 Acts.<br/>1 Anthem.
                    </h1>
                    <p className="text-base w-3/4">
                        We're raising awareness about bullying through music - and <b>you</b> help pick the
                        winner!
                    </p>
                </div>

            </div>
        </div>
    )
}
