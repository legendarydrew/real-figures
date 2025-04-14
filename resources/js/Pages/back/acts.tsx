import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import React, { useState } from 'react';
import { Edit, Trash } from 'lucide-react';
import { Act, PaginatedResponse } from '@/types';
import axios from 'axios';
import { ActDialog } from '@/components/admin/act-dialog';
import { DeleteActDialog } from '@/components/admin/delete-act-dialog';
import { Pagination } from '@/components/admin/pagination';

// TODO indicate whether the Act has a profile.
// TODO display the Act picture, or a placeholder if not present.

export default function Acts({ acts }: Readonly<{ acts: PaginatedResponse<Act> }>) {

    const [currentAct, setCurrentAct] = useState<Act>();
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);

    const pageHandler = (pageNumber: number): void => {
        console.log('change page', pageNumber);
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

    return (
        <AppLayout>
            <Head title="Acts"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Acts</h1>
                <div className="flex gap-1">
                    <Button onClick={() => editHandler()}>Create Act</Button>
                </div>
            </div>

            <Pagination results={acts} onPageChange={pageHandler}/>

            <div className="flex flex-wrap p-4 gap-1">
                {acts.data.map((act) => (
                    <div key={act.id}
                         className="flex rounded-md py-2 px-3 b-2 h-[260px] w-full md:w-1/2 lg:w-1/4 bg-gray-200 hover:bg-gray-300 items-center flex-col justify-end">
                        <div className="w-full p-1 flex justify-between align-end gap-1">
                            <span
                                className="flex-grow text-lg leading-none font-bold text-left text-shadow-md">{act.name}</span>
                            <Button variant="secondary" size="icon" className="cursor-pointer"
                                    onClick={() => editHandler(act)}
                                    title="Edit Act">
                                <Edit className="h-3 w-3"/>
                            </Button>
                            <Button variant="destructive" size="icon" className="cursor-pointer"
                                    onClick={() => deleteHandler(act)}
                                    title="Delete Act">
                                <Trash className="h-3 w-3"/>
                            </Button>
                        </div>
                    </div>
                ))}
            </div>

            <ActDialog act={currentAct} open={isEditDialogOpen} onOpenChange={() => setIsEditDialogOpen(false)}/>
            <DeleteActDialog act={currentAct} open={isDeleteDialogOpen}
                             onOpenChange={() => setIsDeleteDialogOpen(false)}/>
        </AppLayout>
    );
}
