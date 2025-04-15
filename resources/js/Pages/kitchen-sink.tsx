import { Head } from '@inertiajs/react';
import { PaypalButton } from '@/components/paypal-button';

export default function KitchenSinkPage() {

    return (
        <>
            <Head title="Welcome to the Kitchen Sink!">
                <link rel="preconnect" href="https://fonts.bunny.net"/>
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
            </Head>
            <div
                className="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
                <header className="mb-6">
                    <h1 className="text-2xl font-bold">Welcome to the Kitchen Sink!</h1>
                </header>
                <main className="flex-grow w-full lg:max-w-[120em]">

                    <section className="my-3">
                        <h2 className="text-base font-bold mb-1">Donate button</h2>
                        <PaypalButton/>
                    </section>

                </main>
                <footer className="text-center text-xs">
                    Copyright &copy; Drew Maughan (SilentMode).
                </footer>
            </div>
        </>
    );
}
