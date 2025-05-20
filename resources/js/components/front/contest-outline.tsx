import HeadingSmall from '@/components/heading-small';

export default function ContestOutline() {
    return (
        <div className="bg-gray-500 text-white py-10 px-5">
            <div className="max-w-5xl mx-auto grid gap-5 md:grid-cols-2 lg:grid-cols-3">

                <div>
                    <HeadingSmall title="Stage 1: Knockout Rounds"/>
                    <ul className="list-disc text-sm mx-3 mb-3">
                        <li>Acts submit Songs based on special lyrics.</li>
                        <li>8 Rounds, 4 Songs each.</li>
                        <li>Vote for your <b>top 3 favourites</b> in each Round.</li>
                        <li>8 Round winners + 2 top runners-up = 10 finalists!</li>
                    </ul>
                </div>

                <div>
                    <HeadingSmall title="Stage 2: Finals"/>
                    <ul className="list-disc text-sm mx-3 mb-3">
                        <li>Finalists update their Songs with a <b>new second verse</b>.</li>
                        <li>Vote again for your <b>top 3 favourites</b>.</li>
                        <li>1 Grand Winner + 3 Runners-up will be crowned!</li>
                    </ul>
                </div>

                <div>
                    <HeadingSmall title="🏆 Prizes"/>
                    <ul className="list-disc text-sm mx-3 mb-3">
                        <li>Winning Acts become <b>3D-printed figures</b>.</li>
                        <li>The Grand Winner's Act gets a <b>custom LEGO minifigure!</b></li>
                        <li>The Winning Song becomes our <b>official anthem</b>.</li>
                    </ul>
                </div>
            </div>
        </div>
    )
}
