import { FC, useEffect, useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Link, usePage } from '@inertiajs/react';
import { PaypalButton } from '@/components/paypal-button';
import { Checkbox } from '@/components/ui/checkbox';
import { Textarea } from '@/components/ui/textarea';

interface DonateDialogProps {
    // Dialog properties.
    open: boolean;
    onOpenChange: () => void;
}

export const DonateDialog: FC<DonateDialogProps> = ({ open, onOpenChange }) => {

    const { donation } = usePage().props;

    const [amount, setAmount] = useState<number>(donation.default.general);
    const [message, setMessage] = useState<string>('');
    const [isAnonymous, setIsAnonymous] = useState<boolean>(false);
    const [wasSuccessful, setWasSuccessful] = useState<boolean>(false);
    const [failed, setFailed] = useState<boolean>(false);

    const processingHandler = () => {
        setWasSuccessful(false);
        setFailed(false);
    };

    const successHandler = () => {
        setWasSuccessful(true);
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
        <Dialog open={open} onOpenChange={onOpenChange}>
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

                {wasSuccessful ? (<>
                    <div className="h-1/3 p-10 flex items-center justify-center text-green-600 font-semibold">
                        Thank you for your donation!
                    </div>
                    <DialogFooter className="items-center md:justify-between md:flex-row-reverse">
                        <Button variant="ghost" type="button" onClick={onOpenChange}>Close</Button>
                    </DialogFooter>
                </>) : (
                    <>
                        <div className="flex gap-3 justify-between items-center py-2 px-5 bg-green-200 rounded-sm">
                            <Label htmlFor="donationAmount">I would like to donate</Label>

                            <div className="flex gap-1 items-center">
                                {donation.options.map((value) => (
                                    <Button key={value} variant="secondary" type="button"
                                            onClick={() => setAmount(value)}>{value}</Button>
                                ))}
                                <div className="ml-2 flex items-center">
                                    <Input
                                        className="bg-white w-[5rem] border-green-500 text-green-800 font-semibold text-right text-lg"
                                        id="donationAmount" type="number" value={amount}
                                        onChange={setAmount}/>
                                    <span
                                        className="p-1 font-semibold text-base">{donation.currency}</span>
                                </div>
                            </div>
                        </div>

                        <div className="flex-grow">
                            <Label className="mb-2" htmlFor="donationMessage">A message for SilentMode <small
                                className="font-normal">(optional)</small></Label>
                            <Textarea id="donationMessage" value={message} onChange={setMessage} rows={2}/>
                        </div>

                        <div className="flex gap-2 items-center">
                            <Checkbox id="donationAnonymous" className="bg-white" checked={isAnonymous}
                                      onChange={setIsAnonymous}/>
                            <Label htmlFor="donationAnonymous">I would like to remain anonymous.</Label>
                        </div>

                        {failed && (
                            <p className="p-2 font-semibold rounded-md bg-destructive/10 text-destructive text-sm">Something
                                went wrong with processing your donation, please try again.</p>
                        )}

                        <DialogFooter className="items-center md:justify-between md:flex-row-reverse">
                            <PaypalButton amount={amount} currency={donation.currency}
                                          description="Real Figures Don't F.O.L.D donation"
                                          onProcessing={processingHandler}
                                          onSuccess={successHandler}
                                          onFailure={failureHandler}/>
                            <Button variant="ghost" type="button" onClick={onOpenChange}>Cancel</Button>
                        </DialogFooter>
                    </>)}
            </DialogContent>
        </Dialog>
    )
}
