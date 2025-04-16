import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import React, { useEffect, useState } from 'react';
import { ChevronDown, ChevronUp, Edit, Trash } from 'lucide-react';
import { Song } from '@/types';
import { SongDialog } from '@/components/admin/song-dialog';
import { DeleteSongDialog } from '@/components/admin/delete-song-dialog';
import { LanguageFlag } from '@/components/language-flag';
import { Pagination } from '@/components/admin/pagination';
import { Icon } from '@/components/icon';

// TODO filter songs.

interface TableSort {
    column: string;
    asc: boolean;
}

export default function Songs({ acts, songs }: Readonly<{ stages: Song[] }>) {

    const [currentSong, setCurrentSong] = useState<Song>();
    const [sort, setSort] = useState<TableSort>({ column: 'title', asc: true });
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);

    const pageHandler = (pageNumber: number): void => {
        fetchSongs(pageNumber);
    };

    const sortHandler = (column: string): void => {
        setSort({ column, asc: column === sort.column ? !sort.asc : sort.asc });
    };

    const fetchSongs = (pageNumber: number = 1): void => {
        router.reload({
            data: {
                page: pageNumber ?? songs.meta.pagination.current_page,
                sort: `${sort.column}:${sort.asc ? 'asc' : 'desc'}`
            },
            only: ['songs'],
            showProgress: true
        });
    }

    useEffect(() => {
        fetchSongs();
    }, [sort]);

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
                    <th scope="col" className="text-left px-2 select-none cursor-pointer"
                        onClick={() => sortHandler('title')}>
                        Title
                        {sort.column === 'title' ? (
                            <Icon iconNode={sort.asc ? ChevronUp : ChevronDown} className="h-3 inline"/>) : ''}
                    </th>
                    <th scope="col" className="text-left px-2 select-none cursor-pointer"
                        onClick={() => sortHandler('acts.name')}>
                        Act
                        {sort.column === 'acts.name' ? (
                            <Icon iconNode={sort.asc ? ChevronUp : ChevronDown} className="h-3 inline"/>) : ''}
                    </th>
                    <th scope="col" className="text-right px-2 select-none cursor-pointer"
                        onClick={() => sortHandler('play_count')}>
                        Play count
                        {sort.column === 'play_count' ? (
                            <Icon iconNode={sort.asc ? ChevronUp : ChevronDown} className="h-3 inline"/>) : ''}
                    </th>
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
