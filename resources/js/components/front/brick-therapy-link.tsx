import { Link } from '@inertiajs/react';

export const BrickTherapyLink: React.FC = ({ className }) => {

    return (
        <Link className={className} target="_blank" href="https://discord.gg/PXGrTBtKS6">
            <img src="/img/brick-therapy-discord.png" alt="Join the Brick Therapy Discord group."/>
        </Link>
    );
}
