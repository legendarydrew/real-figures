import { Act } from '@/types';
import { Head, router } from '@inertiajs/react';
import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {LoadingButton} from '@/components/ui/loading-button';

export default function Acts({ act }: Readonly<{ act: Act }>) {

    const cancelHandler = (): void => {
        router.visit(route('admin.acts'));
    };

    return (
        <AppLayout>
            <Head title="Create Act"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Create Act</h1>
            </div>

            <form className="flex flex-col gap-3 px-4">

                <div className="flex justify-between">
                    <Button variant="ghost" type="button" size="lg" onClick={cancelHandler}>Cancel</Button>
                    <LoadingButton size="lg">Save Act</LoadingButton>
                </div>
            </form>

        </AppLayout>
    );
};
