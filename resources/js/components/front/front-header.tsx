import { Link, usePage } from '@inertiajs/react';
import type { SharedData } from '@/types';

export const FrontHeader: React.FC = () => {
    const { auth } = usePage<SharedData>().props;

    return (
        <header className="border-b-1 py-2 w-full shadow-sm">
            <div className="max-w-5xl mx-auto flex items-center justify-between gap-2">

                <Link href={route('home')} className="font-bold">
                    CATAWOL Records
                </Link>

                <nav className="flex items-center justify-end gap-4">
                    {auth.user ? (
                        <Link
                            href={route('admin.dashboard')}
                            className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                        >
                            Dashboard
                        </Link>
                    ) : (
                        <Link
                            href={route('login')}
                            className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                        >
                            Log in
                        </Link>
                    )}
                </nav>

            </div>
        </header>
    );

}
