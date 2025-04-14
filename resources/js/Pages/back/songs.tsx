import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import React, { useState } from 'react';
import { Edit, Trash } from 'lucide-react';
import { Song } from '@/types';
import { SongDialog } from '@/components/admin/song-dialog';
import { DeleteSongDialog } from '@/components/admin/delete-song-dialog';
import { LanguageFlag } from '@/components/language-flag';
import { Pagination } from '@/components/admin/pagination';

// TODO sort songs.
// TODO filter songs.

export default function Songs({ acts, songs }: Readonly<{ stages: Song[] }>) {

    const [currentSong, setCurrentSong] = useState<Song>();
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);

    const pageHandler = (pageNumber: number): void => {
        router.reload({ data: { page: pageNumber } });
    };

    const editHandler = (stage: Song): void => {
        setCurrentSong(stage);
        setIsEditDialogOpen(true);
    }

    const deleteHandler = (stage: Song): void => {
        setCurrentSong(stage);
        setIsDeleteDialogOpen(true);
    }

    return (
        <AppLayout>
            <Head title="Songs"/>

            <div className="flex mb-3 p-4">
                <h1 className="flex-grow font-bold text-2xl">Songs</h1>
                <div className="flex gap-1">
                    <Button onClick={editHandler}>Add Song</Button>
                </div>
            </div>

            <table className="mx-4 mb-8">
                <thead className="text-sm">
                <tr className="border-b-2">
                    <th/>
                    <th scope="col" className="text-left px-2">Title</th>
                    <th scope="col" className="text-left px-2">Act</th>
                    <th scope="col" className="text-right px-2">Play count</th>
                    <th/>
                </tr>
                </thead>
                <tbody>
                {songs.data.map((song) => (
                    <tr className="hover:bg-accent/50 select-none" key={song.id}>
                        <th>
                            <LanguageFlag languageCode={song.language}/>
                        </th>
                        <th scope="row" className="font-bold text-left px-2 py-1">
                            {song.title}
                        </th>
                        <td className="text-left px-2 py-1">{song.act.name}</td>
                        <td className="text-sm text-right px-2 py-1">{song.play_count.toLocaleString()}</td>
                        <td className="px-2 py-1">
                            <div className="flex justify-end gap-1">
                                <Button variant="secondary" className="p-3 cursor-pointer"
                                        onClick={() => editHandler(song)}
                                        title="Edit Song">
                                    <Edit className="h-3 w-3"/>
                                </Button>
                                <Button variant="destructive" className="p-3 cursor-pointer"
                                        onClick={() => deleteHandler(song)}
                                        title="Delete Song">
                                    <Trash className="h-3 w-3"/>
                                </Button>
                            </div>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            <Pagination results={songs} onPageChange={pageHandler}/>

            <SongDialog song={currentSong} acts={acts} open={isEditDialogOpen}
                        onOpenChange={() => setIsEditDialogOpen(false)}/>
            <DeleteSongDialog song={currentSong} open={isDeleteDialogOpen}
                              onOpenChange={() => setIsDeleteDialogOpen(false)}/>
        </AppLayout>
    );
}
