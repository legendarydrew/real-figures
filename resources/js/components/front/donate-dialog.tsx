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
        globalThis.trackEvent("donation", {
            value: amount,
            anonymous: isAnonymous
        });
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
            <div className="donate-dialog-success">
                <div className="donate-dialog-success-inner">
                    <ConfettiExplosion {...confettiSettings} />
                </div>
                Thank you for your donation!
            </div>
        ) : (
            <div className="donate-dialog-content">
                <div className="donate-dialog-donate">

                    <Label htmlFor="donationAmount">I would like to donate</Label>

                    <div className="donate-dialog-donate-options">
                        {donationOptions.current.map((value) => (
                            <Button key={value} variant="donate" type="button" size="sm"
                                    onClick={() => setAmount(value)}>{value}</Button>
                        ))}
                        <div className="donate-dialog-donate-amount">
                            <Input id="donationAmount" type="number" value={amount} min={donation.minimum.general}
                                   onChange={amountHandler}/>
                            <span className="donate-dialog-donate-currency">{donation.currency}</span>
                        </div>
                    </div>

                    <p className="donate-dialog-donate-min">We're asking for a minimum donation
                        of <b>{donation.currency} {donation.minimum.general}</b>.</p>

                </div>

                <div className="donate-dialog-message">
                    <Label htmlFor="donationMessage">A message for SilentMode <small>(optional)</small></Label>
                    <Textarea id="donationMessage" autoFocus value={message} onChange={messageHandler} rows={2}/>
                </div>

                {
                    failed && (
                        <Alert type="error"
                               message="Something went wrong with processing your donation, please try again."/>
                    )
                }

                <footer className="donate-dialog-footer">
                    <div className="donate-dialog-anonymous">
                        <Checkbox id="donationAnonymous" checked={isAnonymous} onCheckedChange={anonymousHandler}/>
                        <Label htmlFor="donationAnonymous">I would like to remain anonymous.</Label>
                    </div>

                    <PaypalButton amount={amount} currency={donation.currency}
                                  minimumAmount={donation.minimum.general}
                                  additionalData={{ is_anonymous: isAnonymous, message }}
                                  description="Real Figures Don't F.O.L.D: donation"
                                  onProcessing={processingHandler}
                                  onSuccess={successHandler}
                                  onFailure={failureHandler}/>
                </footer>
            </div>
        )
    )
        ;
}
