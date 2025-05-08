import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';

export default function GoldenBuzzerBanner() {
    return (
        <div className="bg-yellow-700 text-white py-10 px-5">
            <div className="max-w-5xl mx-auto flex items-center gap-4">

                <PlaceholderPattern className="stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                <div className="w-1/2 ml-10">
                    <h2 className="display-text text-2xl mb-2">Golden Buzzer</h2>
                    <p>Love a Song? <b>Golden Buzzer it</b> with a donation to give it extra honours (without
                        affecting the vote count)!</p>
                </div>
            </div>
        </div>
    )
}
