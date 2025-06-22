import CatawolIconLogo from '@/components/mode/catawol-icon-logo';

export default function AppLogo() {
    return (
        <>
            <div
                className="flex aspect-square h-8 items-center justify-center">
                <CatawolIconLogo className="size-7"/>
            </div>
            <div className="ml-1 grid flex-1 text-left text-base">
                <span className="mb-0.5 leading-none font-semibold">RFDF Song Contest</span>
            </div>
        </>
    );
}
