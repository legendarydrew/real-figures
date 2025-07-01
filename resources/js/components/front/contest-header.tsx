export default function ContestHeader({ children }) {
    return (
        <div
            className="bg-linear-to-b from-slate-500 to-indigo-800 text-white dark:bg-indigo-800/60 px-5">
            <div className="max-w-5xl mx-auto flex flex-col md:flex-row items-end gap-4">

                <div className="w-2/5 overflow-hidden h-full">
                    <img className="w-full" src="/img/microphone.png" alt="A microphone on a stand."/>
                </div>

                <div className="px-3 py-5 md:py-10 md:px-0 md:w-1/2 md:ml-10">
                    <h1 className="display-text text-4xl text-shadow-md mb-3">
                        32 Acts.<br/>1 Anthem.
                    </h1>
                    <p className="text-base mb-3 md:w-3/4">
                        We're raising awareness about bullying through music - and <b>you</b> help pick the
                        winner!
                    </p>

                    {children}
                </div>

            </div>
        </div>
    )
}
