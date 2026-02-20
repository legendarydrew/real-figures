import React, { ChangeEvent, useEffect, useRef, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { PaypalButton } from '@/components/mode/paypal-button';
import { Checkbox } from '@/components/ui/checkbox';
import { Textarea } from '@/components/ui/textarea';
import { Alert } from '@/components/mode/alert';
import ConfettiExplosion, { ConfettiProps } from 'react-confetti-explosion';

export const DonateDialog: React.FC = () => {

    const donation = {
        'default': {
            'general': 5,
            'golden_buzzer': 10
        },
        'minimum': {
            'general': 1,
            'golden_buzzer': 6
        },
        'options': [3, 5, 10, 15, 20, 50, 100],
        'currency': 'USD'
    };

    const donationOptions = useRef<number[]>(donation.options.filter((v) => v >= donation.minimum.general));
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
        setAmount(Number.parseFloat(e.target.value));
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
        wasSuccessful ? (
            <div
                className="h-1/3 p-10 flex flex-col text-center items-center justify-center text-green-600 font-semibold">
                <div className="mx-auto relative">
                    <ConfettiExplosion {...confettiSettings} />
                </div>
                Thank you for your donation!
            </div>
        ) : (
            <div className="flex flex-col gap-4">
                <div
                    className="flex flex-col gap-2 p-4 bg-green-200 dark:bg-green-700">

                    <Label htmlFor="donationAmount">I would like to donate</Label>

                    <div className="flex flex-col lg:flex-row gap-3 justify-between items-center">

                        <div className="flex gap-1 items-center">
                            {donationOptions.current.map((value) => (
                                <Button className="max-sm:hidden" key={value} variant="secondary" type="button"
                                        onClick={() => setAmount(value)}>{value}</Button>
                            ))}
                            <div className="ml-2 flex items-center">
                                <Input
                                    className="bg-white w-[5rem] border-green-500 text-green-800 font-semibold text-right text-lg"
                                    id="donationAmount" type="number" value={amount}
                                    min={donation.minimum.general}
                                    onChange={amountHandler}/>
                                <span
                                    className="p-1 font-semibold text-base">{donation.currency}</span>
                            </div>
                        </div>
                    </div>

                    <p className="text-xs text-center w-5/6 mx-auto text-green-700 dark:text-green-200">We're
                        asking for a minimum
                        donation
                        of <b>{donation.currency} {donation.minimum.general}</b>.</p>

                </div>

                <div className="flex-grow flex flex-col gap-2 max-sm:hidden">
                    <Label htmlFor="donationMessage">A message for SilentMode <small
                        className="font-normal">(optional)</small></Label>
                    <Textarea id="donationMessage" autoFocus value={message} onChange={messageHandler} rows={2}/>
                </div>

                {failed && (
                    <Alert type="error"
                           message="Something went wrong with processing your donation, please try again."/>
                )}

                <div className="flex gap-2 items-center">
                    <Checkbox id="donationAnonymous" className="bg-white" checked={isAnonymous}
                              onCheckedChange={anonymousHandler}/>
                    <Label htmlFor="donationAnonymous">I would like to remain anonymous.</Label>
                </div>

                <PaypalButton amount={amount} currency={donation.currency}
                              minimumAmount={donation.minimum.general}
                              additionalData={{ is_anonymous: isAnonymous, message }}
                              description="Real Figures Don't F.O.L.D: donation"
                              onProcessing={processingHandler}
                              onSuccess={successHandler}
                              onFailure={failureHandler}/>

            </div>
        )
    );
}
