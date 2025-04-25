import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { useState } from 'react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Computer, Copyright, User } from 'lucide-react';

export const FrontFooter: React.FC = () => {

    const [isOpen, setIsOpen] = useState(false);

    const closeDialog = () => setIsOpen(false);

    return (
        <footer className="py-3 px-5 text-xs text-center">
            <div className="max-w-5xl mx-auto">
                Copyright &copy; Drew Maughan (SilentMode), all rights reserved.
                <Button variant="link" className="text-xs py-0" onClick={() => setIsOpen(true)}>Full credits</Button>
            </div>

            <Dialog open={isOpen} onOpenChange={closeDialog}>
                <DialogContent className="lg:max-w-3xl">
                    <DialogTitle>Project Credits</DialogTitle>
                    <DialogDescription>
                        <span className="font-semibold">CATAWOL Records presents: Real Figures Don't F.O.L.D</span><br/>
                        An advocacy project in the form of a song contest, calling attention to <b>bullying in adult
                        hobbies</b>, especially within the LEGO space.
                    </DialogDescription>

                    <div className="flex gap-5">
                        <PlaceholderPattern
                            className="w-1/3 h-full flex-shrink-0 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                        <div>
                            <div className="flex gap-5 mb-5">
                                <User className="w-5 flex-shrink-0"/>
                                <ul className="text-sm flex flex-col gap-1">
                                    <li>Inspired by YouTubers <Link href="https://www.youtube.com/@littlelego"
                                                                    className="hover:underline" target="_blank">Little
                                        Lego</Link> and <Link href="https://www.youtube.com/@Never2old4lego"
                                                              className="hover:underline"
                                                              target="_blank">Never2old4lego</Link>.
                                    </li>
                                    <li>Created by Drew Maughan (SilentMode).</li>
                                    <li>Lyrics by Drew Maughan (SilentMode).</li>
                                </ul>
                            </div>
                            <div className="flex gap-5 mb-5">
                                <Computer className="w-5 flex-shrink-0"/>
                                <ul className="text-sm flex flex-col gap-1">
                                    <li className="font-italic">Songs were AI generated using <Link
                                        href="https://udio.com" className="hover:underline"
                                        target="_blank">Udio</Link>.
                                    </li>
                                    <li>Character designs aided by <Link href="https://www.imagine.art/"
                                                                         className="hover:underline"
                                                                         target="_blank">ImagineArt</Link>.
                                    </li>
                                    <li>Act profiles created with the help of <Link href="https://chat.openai.com/"
                                                                                    className="hover:underline"
                                                                                    target="_blank">ChatGPT</Link>.
                                    </li>
                                </ul>
                            </div>
                            <div className="flex gap-5 mb-5">
                                <Copyright className="w-5 flex-shrink-0"/>
                                <div className="text-sm">
                                    <p className="mb-1">All songs are the property of Drew Maughan (SilentMode).</p>
                                    <p>SilentMode, the SilentMode logo, CATAWOL Records, the CATAWOL
                                        Records logo, "Real Figures Don't F.O.L.D", "F.O.L.D", act names and character
                                        designs are &copy; and &trade; Drew Maughan (SilentMode).
                                    </p>
                                </div>
                            </div>

                            <ul className="text-sm flex flex-col gap-1">
                                <li>Designed and developed by Perfect Zero Labs.</li>
                                <li className="text-bold">#givecredit</li>
                            </ul>
                        </div>
                    </div>
                </DialogContent>
            </Dialog>
        </footer>
    )
}
