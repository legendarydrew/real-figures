import { FrontHeader } from '@/components/front/front-header';
import { FrontFooter } from '@/components/front/front-footer';
import { ComponentProps } from 'react';

// see https://inertiajs.com/pages#persistent-layouts

export default ({ children }: ComponentProps<never>) => (
    <div
        className="flex h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">

        <FrontHeader/>
        <main className="flex-grow w-full overflow-y-auto">
            {children}
        </main>
        <FrontFooter/>
    </div>
);
