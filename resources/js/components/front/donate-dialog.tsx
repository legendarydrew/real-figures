import { ChangeEvent, FC, useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Link, usePage } from '@inertiajs/react';
import { PaypalButton } from '@/components/paypal-button';
import { Checkbox } from '@/components/ui/checkbox';
import { Textarea } from '@/components/ui/textarea';
import { Alert } from '@/components/alert';
import { useDialog } from '@/context/dialog-context';
import { useAnalytics } from '@/hooks/use-analytics';
import ConfettiExplosion, { ConfettiProps } from 'react-confetti-explosion';

interface DonateDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
}

export const DONATE_DIALOG_NAME = 'donate';

export const DonateDialog: FC<DonateDialogProps> = () => {

    const { openDialogName, closeDialog } = useDialog();
    const isOpen = openDialogName === DONATE_DIALOG_NAME;

    const { donation } = usePage().props;
    const { trackEvent } = useAnalytics();

    const [amount, setAmount] = useState<number>(donation.default.general);
    const [message, setMessage] = useState<string>('');
    const [isAnonymous, setIsAnonymous] = useState<boolean>(false);
    const [wasSuccessful, setWasSuccessful] = useState<boolean>(false);
    const [failed, setFailed] = useState<boolean>(false);

    const confettiSettings: ConfettiProps = {
        force: 0.9,
        duration: 5000,
        particleCount: 250,
        width: window.innerWidth,
        colors: [
            '#0B6623',
            '#29AB87',
            '#50C878',
            '#B2EC5D'
        ],
        zIndex: 100
    };

    const amountHandler = (e: ChangeEvent): void => {
        setAmount(parseFloat(e.target.value));
    };

    const messageHandler = (e: ChangeEvent): void => {
        setMessage(e.target.value);
    };

    const anonymousHandler = (state: boolean): void => {
        setIsAnonymous(state);
    };

    const processingHandler = () => {
        setWasSuccessful(false);
        setFailed(false);
    };

    const successHandler = () => {
        setWasSuccessful(true);
        trackEvent({ category: 'Action', action: 'Donation', value: amount, nonInteraction: false });
    };

    const failureHandler = () => {
        setFailed(true);
    };

    useEffect(() => {
        if (open) {
            setAmount(donation.default.general);
            setMessage('');
            setWasSuccessful(false);
            setFailed(false);
        }
    }, [open]);

    return (
        <Dialog open={isOpen} onOpenChange={closeDialog}>
            <DialogContent className="lg:max-w-3xl">
                <DialogTitle>Make a Donation</DialogTitle>
                <DialogDescription>
                    <b className="font-semibold">Donate to SilentMode</b> to support his efforts: whether you appreciate
                    the work that went into building this project, the songs, or SilentMode himself.<br/>
                    You can also donate directly to <Link className="underline font-semibold"
                                                          href="https://www.kidscape.org.uk/support-us/donate"
                                                          target="_blank">Kidscape</Link>: a
                    London-based charity with a focus on anti-bullying and child safety.
                </DialogDescription>

                {wasSuccessful ? (
                    <div
                        className="h-1/3 p-10 flex flex-col text-center items-center justify-center text-green-600 font-semibold">
                        <div className="mx-auto relative">
                            <ConfettiExplosion {...confettiSettings} />
                        </div>
                        Thank you for your donation!
                    </div>
                ) : (
                    <>
                        <div
                            className="flex flex-col lg:flex-row gap-3 justify-between items-center py-2 px-5 bg-green-200 dark:bg-green-700 rounded-sm">
                            <Label htmlFor="donationAmount">I would like to donate</Label>

                            <div className="flex gap-1 items-center">
                                {donation.options.map((value) => (
                                    <Button className="max-sm:hidden" key={value} variant="secondary" type="button"
                                            onClick={() => setAmount(value)}>{value}</Button>
                                ))}
                                <div className="ml-2 flex items-center">
                                    <Input
                                        className="bg-white w-[5rem] border-green-500 text-green-800 font-semibold text-right text-lg"
                                        id="donationAmount" type="number" value={amount} min="1"
                                        onChange={amountHandler}/>
                                    <span
                                        className="p-1 font-semibold text-base">{donation.currency}</span>
                                </div>
                            </div>
                        </div>

                        <div className="flex-grow max-sm:hidden">
                            <Label className="mb-2" htmlFor="donationMessage">A message for SilentMode <small
                                className="font-normal">(optional)</small></Label>
                            <Textarea id="donationMessage" value={message} onChange={messageHandler} rows={2}/>
                        </div>

                        {failed && (
                            <Alert type="error"
                                   message="Something went wrong with processing your donation, please try again."/>
                        )}

                        <DialogFooter className="mt-0 items-center md:justify-between">
                            <div className="flex gap-2 items-center">
                                <Checkbox id="donationAnonymous" className="bg-white" checked={isAnonymous}
                                          onCheckedChange={anonymousHandler}/>
                                <Label htmlFor="donationAnonymous">I would like to remain anonymous.</Label>
                            </div>

                            <PaypalButton amount={amount} currency={donation.currency}
                                          additionalData={{ is_anonymous: isAnonymous, message }}
                                          description="Real Figures Don't F.O.L.D donation"
                                          onProcessing={processingHandler}
                                          onSuccess={successHandler}
                                          onFailure={failureHandler}/>
                        </DialogFooter>
                    </>)}
            </DialogContent>
        </Dialog>
    )
}
