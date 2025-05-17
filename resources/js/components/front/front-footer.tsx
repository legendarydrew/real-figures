import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import React, { useState } from 'react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Computer, User } from 'lucide-react';
import HeadingSmall from '@/components/heading-small';

export const FrontFooter: React.FC = () => {

    const [isOpen, setIsOpen] = useState(false);

    const closeDialog = () => setIsOpen(false);

    return (
        <footer className="py-2 md:py-1 px-5 text-xs text-center">
            <div className="max-w-5xl mx-auto">
                Copyright &copy; Drew Maughan (SilentMode), all rights reserved.
                <Button variant="link" className="text-xs py-0 lh-none h-auto" onClick={() => setIsOpen(true)}>Full
                    credits</Button>
            </div>

            <Dialog open={isOpen} onOpenChange={closeDialog}>
                <DialogContent className="lg:max-w-3xl">
                    <DialogTitle>Project Credits</DialogTitle>

                    <PlaceholderPattern
                        className="w-full h-30 flex-shrink-0 stroke-neutral-900/20 dark:stroke-neutral-100/20"/>

                    <DialogDescription>
                        <span className="font-semibold">CATAWOL Records presents: Real Figures Don't F.O.L.D</span><br/>
                        An upskilling and advocacy project in the form of a song contest, calling attention to <b>bullying
                        in adult
                        hobbies</b>, especially within the LEGO space.
                    </DialogDescription>

                    <div className="grid lg:grid-cols-2 gap-5">

                        <div>
                            <div className="flex gap-2 items-center mb-1">
                                <User className="w-5 flex-shrink-0"/>
                                <HeadingSmall title="People"/>
                            </div>
                            <dl className="text-sm md:grid grid-cols-3 gap-x-2 gap-y-1">
                                <dt className="font-semibold col-span-1">Created by</dt>
                                <dd className="font-semibold col-span-2">Drew Maughan (SilentMode)</dd>
                                <dt className="font-semibold col-span-1">Lyrics</dt>
                                <dd className="font-semibold col-span-2">Drew Maughan (SilentMode)</dd>
                                <dt className="font-semibold col-span-1">Inspired by</dt>
                                <dd className="col-span-2">
                                    <Link href="https://www.youtube.com/@littlelego"
                                          className="hover:underline" target="_blank">Little
                                        Lego</Link> and <Link href="https://www.youtube.com/@Never2old4lego"
                                                              className="hover:underline"
                                                              target="_blank">Never2old4lego</Link>
                                </dd>
                            </dl>
                        </div>

                        <div>
                            <div className="flex gap-2 items-center mb-1">
                                <Computer className="w-5 flex-shrink-0"/>
                                <HeadingSmall title="AI"/>
                            </div>
                            <dl className="text-sm md:grid grid-cols-2 gap-x-2 gap-y-1">
                                <dt className="font-semibold col-span-1">Song creation</dt>
                                <dd className="col-span-1">
                                    <Link href="https://udio.com" className="hover:underline"
                                          target="_blank">Udio</Link>
                                </dd>
                                <dt className="font-semibold col-span-1">Lyric translations</dt>
                                <dd className="col-span-1">
                                    <Link href="https://generatelyrics.io/" className="hover:underline"
                                          target="_blank">Generate Lyrics</Link>
                                </dd>
                                <dt className="font-semibold col-span-1">Act designs (inspiration)</dt>
                                <dd className="col-span-1">
                                    <Link href="https://www.imagine.art/" className="hover:underline"
                                          target="_blank">ImagineArt</Link>
                                </dd>
                                <dt className="font-semibold col-span-1">Content aid</dt>
                                <dd className="col-span-1">
                                    <Link href="https://chat.openai.com/" className="hover:underline"
                                          target="_blank">ChatGPT</Link>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div className="text-xs text-center flex flex-col gap-1">
                        <p>All songs are the property of Drew Maughan (SilentMode).</p>
                        <p>SilentMode, the SilentMode logo, CATAWOL Records, the CATAWOL
                            Records logo, "Real Figures Don't F.O.L.D", "F.O.L.D", act names and character
                            designs are &copy; Drew Maughan (SilentMode).
                        </p>
                        <p>LEGO is a registered trademark of The LEGO Group, which has no involvement with ths
                            project.</p>
                        <p>
                            Designed and developed by Perfect Zero Labs. <Link className="font-semibold hover:underline"
                                                                               href="https://www.youtube.com/watch?v=4paaA69PFPk"
                                                                               target="_blank">#givecredit</Link>
                        </p>
                    </div>
                </DialogContent>
            </Dialog>
        </footer>
    )
}
