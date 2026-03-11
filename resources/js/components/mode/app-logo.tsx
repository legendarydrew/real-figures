import CatawolIconLogo from '@/components/mode/catawol-icon-logo';

export default function AppLogo() {
    return (
        <>
            <div
                className="flex aspect-square h-8 items-center justify-center">
                <CatawolIconLogo className="size-7"/>
            </div>
            <div className="ml-1 grid flex-1 text-left text-base">
                <span className="display-text">RFDF Song Contest</span>
            </div>
        </>
    );
}
