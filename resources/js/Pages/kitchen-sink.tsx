import { PaypalButton } from '@/components/paypal-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head } from '@inertiajs/react';
import { ChangeEvent, useState } from 'react';
import { LoadingButton } from '@/components/ui/loading-button';
import { FlashMessage } from '@/components/flash-message';
import { Alert } from '@/components/alert';

export default function KitchenSinkPage() {
    const [donationAmount, setDonationAmount] = useState(10);
    const [success, setSuccess] = useState<boolean>(false);
    const [failure, setFailure] = useState<boolean>(false);

    const amountChangeHandler = (e: ChangeEvent): void => {
        setDonationAmount(parseFloat(e.target.value));
    };

    const successHandler = (): void => {
        setFailure(false);
        setSuccess(true);
    };

    const failureHandler = (): void => {
        setFailure(true);
        setSuccess(false);
    };

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
                <main className="w-full flex-grow lg:max-w-[120em]">

                    <FlashMessage message="This is a flash message."/>

                    <section className="my-3">
                        <h2 className="mb-1 text-base font-bold">Alerts</h2>

                        <Alert message="Default alert"/>
                        <Alert type="warning" message="Warning alert"/>
                        <Alert type="info" message="Info alert"/>
                        <Alert type="success" message="Success alert"/>
                        <Alert type="error" message="Error alert"/>
                    </section>

                    <section className="my-3">
                        <h2 className="mb-1 text-base font-bold">Donate button</h2>
                        <Label htmlFor="donationAmount">Amount to donate (USD)</Label>
                        <Input className="w-[10em]" id="donationAmount" type="number" onChange={amountChangeHandler}/>
                        <PaypalButton amount={donationAmount} description="Make My Day" onSuccess={successHandler}
                                      onFailure={failureHandler}/>

                        {success && <p className="text-green-500">Donation was made!</p>}
                        {failure && <p className="text-red-400">Problem with donation.</p>}
                    </section>

                    <section className="my-3">
                        <h2 className="mb-1 text-base font-bold">Loading button</h2>
                        <LoadingButton variant="outline" isLoading={false}>Hello</LoadingButton><br/>
                        <LoadingButton variant="outline" isLoading={true}>Hello</LoadingButton>
                    </section>

                </main>
                <footer className="text-center text-xs">Copyright &copy; Drew Maughan (SilentMode).</footer>
            </div>
        </>
    );
}
