import { Link, usePage } from '@inertiajs/react';
import type { SharedData } from '@/types';
import CatawolTextLogo from '@/components/catawol-text-logo';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { DONATE_DIALOG_NAME } from '@/components/front/donate-dialog';
import { useDialog } from '@/context/dialog-context';
import { MenuIcon, XIcon } from 'lucide-react';
import { useState } from 'react';

export const FrontHeader: React.FC = () => {

    const { auth } = usePage<SharedData>().props;

    const { openDialog } = useDialog();

    const [menuIsOpen, setMenuIsOpen] = useState(false);

    const showDonateDialog = (): void => {
        closeMenuHandler();
        openDialog(DONATE_DIALOG_NAME);
    }

    // Responsive menu functionality adapted from
    // https://www.kindacode.com/article/tailwind-css-create-a-responsive-top-navigation-menu
    const openMenuHandler = (): void => {
        setMenuIsOpen(true);
    };

    const closeMenuHandler = (): void => {
        setMenuIsOpen(false);
    };

    const menuClasses = (): string => {
        return cn("flex items-center justify-end gap-2",
            menuIsOpen ?
                "w-full h-screen fixed top-0 right-0 px-4 py-10 bg-gray-100 z-50 flex-col" :
                "max-md:hidden");
    };

    const linkStyle: string = 'display-text text-lg md:text-sm leading-normal px-3 py-1.5 hover:underline text-[#1b1b18] dark:text-[#EDEDEC]';

    return (
        <header className="border-b-1 py-2 w-full shadow-sm">
            <div className="max-w-5xl mx-auto px-2 lg:px-0 flex items-center justify-between gap-2">

                <Link href={route('home')} className="font-bold">
                    <CatawolTextLogo className="w-auto h-10"/>
                </Link>

                <nav className={menuClasses()}>
                    <Button class="md:hidden z-90 fixed top-4 right-6" type="button" variant="icon"
                            onClick={closeMenuHandler}
                            title="Close Menu">
                        <XIcon/>
                    </Button>

                    <Link href={route('home')} onClick={closeMenuHandler} className={linkStyle}>Contest</Link>
                    <Link href={route('acts')} onClick={closeMenuHandler} className={linkStyle}>Acts</Link>
                    <Link href={route('rules')} onClick={closeMenuHandler} className={linkStyle}>Rules</Link>
                    <Link href={route('about')} onClick={closeMenuHandler} className={linkStyle}>About</Link>
                    <Button type="button" variant="link"
                            className={cn(linkStyle, "text-green-600 dark:text-green-200")}
                            onClick={showDonateDialog}>Donate!</Button>
                    <Link href={route('contact')} onClick={closeMenuHandler} className={linkStyle}>Contact</Link>
                    {auth.user && (
                        <Link
                            href={route('admin.dashboard')}
                            className={cn(linkStyle, "inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:no-underline hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]")}
                        >
                            Dashboard
                        </Link>
                    )}
                </nav>

                {/* "Hamburger" icon for mobile devices. */}
                <Button class="md:hidden" type="button" variant="icon" onClick={openMenuHandler} title="Menu">
                    <MenuIcon/>
                </Button>

            </div>
        </header>
    );

}
