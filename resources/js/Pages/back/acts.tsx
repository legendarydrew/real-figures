import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import React, { useState } from 'react';
import { Act, PaginatedResponse } from '@/types';
import axios from 'axios';
import { ActDialog } from '@/components/admin/act-dialog';
import { Pagination } from '@/components/admin/pagination';
import { ActItem } from '@/components/act-item';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import { Toaster } from '@/components/ui/toast-message';
import { DialogTitle } from '@/components/ui/dialog';
import { Nothing } from '@/components/nothing';

export default function Acts({ acts }: Readonly<{ acts: PaginatedResponse<Act> }>) {

    const [currentAct, setCurrentAct] = useState<Act>();
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);
    const [isDeleting, setIsDeleting] = useState<boolean>(false);

    const pageHandler = (pageNumber: number): void => {
        // Nice!
        router.reload({ data: { page: pageNumber } });
    };

    const editHandler = (act?: Act): void => {
        if (act) {
            // Until I figure out how I can do this with Inertia, use axios to fetch the existing
            // act information for editing in the dialog (because we want to obtain the associated
            // profile, if present).
            axios.get(route('acts.show', { id: act.id }))
                .then((response) => {
                    setCurrentAct(response.data);
                    setIsEditDialogOpen(true);
                });
        } else {
            setCurrentAct(null);
            setIsEditDialogOpen(true);
        }
    }

    const deleteHandler = (act: Act): void => {
        setCurrentAct(act);
        setIsDeleteDialogOpen(true);
    }

    const confirmDeleteHandler = () => {
        if (currentAct) {
            router.delete(route('acts.destroy', { id: currentAct.id }), {
                preserveUrl: true,
                preserveScroll: true,
                onStart: () => {
                    setIsDeleting(true);
                },
                onFinish: () => {
                    setIsDeleting(false);
                },
                onSuccess: () => {
                    Toaster.success(`"${currentAct.name}" was deleted.`);
                    setIsDeleteDialogOpen(false);
                    setCurrentAct(undefined);
                }
            });
        }
    };


    return (
        <AppLayout>
            <Head title="Acts"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Acts</h1>
                <div className="flex gap-1">
                    <Button onClick={() => editHandler()}>Create Act</Button>
                </div>
            </div>

            <Pagination results={acts} onPageChange={pageHandler}/>

            {acts.meta.pagination.total ? (
                <div className="grid auto-rows-min gap-1 md:grid-cols-3 lg:grid-cols-4">
                    {acts.data.map((act) => (
                        <ActItem key={act.id} act={act} editable={true} onEdit={() => editHandler(act)}
                                 onDelete={() => deleteHandler(act)}/>
                    ))}
                </div>
            ) : (
                <Nothing>
                    No Acts defined.
                </Nothing>
            )}

            <ActDialog act={currentAct} open={isEditDialogOpen} onOpenChange={() => setIsEditDialogOpen(false)}/>
            <DestructiveDialog open={isDeleteDialogOpen} onOpenChange={() => setIsDeleteDialogOpen(false)}
                               onConfirm={confirmDeleteHandler} processing={isDeleting}>
                <DialogTitle>{`Delete Act "${currentAct?.name}"`}</DialogTitle>
                <span className="italic">This will also delete Songs associated with this Act,
                    and remove them from existing Rounds.</span><br/>
                Are you sure you want to do this?
            </DestructiveDialog>
        </AppLayout>
    );
}
