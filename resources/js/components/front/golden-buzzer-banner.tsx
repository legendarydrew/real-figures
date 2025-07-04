export default function GoldenBuzzerBanner() {
    return (
        <div className="bg-yellow-700 text-white px-5">
            <div className="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-3 md:gap-4">

                <figure>
                    <img src="/img/golden-buzzer.jpg" className="max-h-50" alt="Pressing the Golden Buzzer."/>
                </figure>

                <div className="p-3 md:p-0 md:w-1/2 md:ml-10">
                    <h2 className="display-text text-2xl mb-2">Golden Buzzer</h2>
                    <p>Love a Song? <b>Golden Buzzer it</b> with a donation to give it extra honours (without
                        affecting the vote count)!</p>
                </div>
            </div>
        </div>
    )
}
