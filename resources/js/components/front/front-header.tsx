import { Link, usePage } from '@inertiajs/react';
import type { SharedData } from '@/types';
import CatawolTextLogo from '@/components/catawol-text-logo';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { DonateDialog } from '@/components/front/donate-dialog';
import { useState } from 'react';

export const FrontHeader: React.FC = () => {
    const { auth } = usePage<SharedData>().props;

    const [isDonateDialogOpen, setIsDonateDialogOpen] = useState<boolean>(false);

    const linkStyle: string = 'text-sm font-semibold leading-normal px-3 py-1.5 hover:underline text-[#1b1b18] dark:text-[#EDEDEC]';

    return (
        <header className="border-b-1 py-2 w-full shadow-sm">
            <div className="max-w-5xl mx-auto flex items-center justify-between gap-2">

                <Link href={route('home')} className="font-bold">
                    <CatawolTextLogo className="w-auto h-10"/>
                </Link>

                <nav className="flex items-center justify-end gap-4">
                    <Link href={route('home')} className={linkStyle}>Contest</Link>
                    <Link href={route('rules')} className={linkStyle}>Rules</Link>
                    <Link href={route('about')} className={linkStyle}>About</Link>
                    <Button type="button" variant="outline" className="text-green-600 font-semibold cursor-pointer"
                            onClick={() => setIsDonateDialogOpen(true)}>Donate!</Button>
                    {auth.user ? (
                        <Link
                            href={route('admin.dashboard')}
                            className={cn(linkStyle, "inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:no-underline hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]")}
                        >
                            Dashboard
                        </Link>
                    ) : (
                        <Link
                            href={route('login')}
                            className={cn(linkStyle, "inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:no-underline hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]")}
                        >
                            Log in
                        </Link>
                    )}
                </nav>

            </div>

            <DonateDialog open={isDonateDialogOpen} onOpenChange={() => setIsDonateDialogOpen(false)}/>
        </header>
    );

}
