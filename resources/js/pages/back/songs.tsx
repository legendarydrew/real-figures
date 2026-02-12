import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import React, { RefObject, useMemo, useRef, useState } from 'react';
import { ChevronDown, ChevronUp, Edit, Music, Trash } from 'lucide-react';
import { PaginatedResponse, Song } from '@/types';
import { SongDialog } from '@/components/admin/song-dialog';
import { LanguageFlag } from '@/components/mode/language-flag';
import { Pagination } from '@/components/admin/pagination';
import { Icon } from '@/components/icon';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import { DialogTitle } from '@/components/ui/dialog';
import { RTToast } from '@/components/mode/toast-message';
import { Nothing } from '@/components/mode/nothing';

interface TableSort {
    column: string;
    asc: boolean;
}

// As a future challenge: can we preserve this page's sort order?
// (perhaps by adding the information to the meta data.)

export default function Songs({ acts, songs }: Readonly<{ songs: PaginatedResponse<Song> }>) {

    const [currentSong, setCurrentSong] = useState<Song>();
    const [isEditDialogOpen, setIsEditDialogOpen] = useState<boolean>(false);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);
    const [isDeleting, setIsDeleting] = useState<boolean>(false);

    // Using refs instead of states, as these do not require re-rendering the page.
    // However, things will look just a little more stiff.
    const currentPage: RefObject<number> = useRef(songs?.meta.pagination.current_page ?? 1);
    const currentSort: RefObject<TableSort> = useRef({ column: 'title', asc: true });

    const pageHandler = (pageNumber: number): void => {
        currentPage.current = pageNumber;
        fetchSongs();
    };

    const sortHandler = (column: string): void => {
        currentSort.current = {
            column,
            asc: column === currentSort.current.column ? !currentSort.current.asc : currentSort.asc
        };
        fetchSongs();
    };

    const fetchSongs = (): void => {
        router.reload({
            data: {
                page: currentPage.current,
                sort: `${currentSort.current.column}:${currentSort.current.asc ? 'asc' : 'desc'}`
            },
            only: ['songs'],
            showProgress: true,
            onSuccess: (response) => {
                currentPage.current = response.props.songs.meta.pagination.current_page;
            }
        });
    }

    const editHandler = (song?: Song): void => {
        setCurrentSong(song);
        setIsEditDialogOpen(true);
    }

    const deleteHandler = (song: Song): void => {
        setCurrentSong(song);
        setIsDeleteDialogOpen(true);
    }

    const sortIcon = useMemo(() => {
        return currentSort.current.asc ? ChevronUp : ChevronDown;
    }, [currentSort.current]);

    const confirmDeleteHandler = () => {
        if (currentSong) {
            router.delete(route('songs.destroy', { id: currentSong.id }), {
                preserveUrl: true,
                preserveScroll: true,
                onStart: () => {
                    setIsDeleting(true);
                },
                onFinish: () => {
                    setIsDeleting(false);
                },
                onSuccess: () => {
                    RTToast.success(`"${currentSong.title}" was deleted.`);
                    setIsDeleteDialogOpen(false);
                    setCurrentSong(undefined);
                }
            });
        }
    };

    return (
        <AppLayout>
            <Head title="Songs"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">Songs</h1>
                <div className="flex gap-1">
                    <Button onClick={() => editHandler()}>Add Song</Button>
                </div>
            </div>

            {songs.meta.pagination.total ? (
                <div className="overflow-x-auto">
                    <table className="dashboard-table">
                        <colgroup>
                            <col style={{ width: '4em' }}/>
                            <col/>
                            <col/>
                            <col style={{ width: '6em' }}/>
                            <col style={{ width: '10em' }}/>
                        </colgroup>
                        <thead className="text-sm">
                        <tr className="border-b-2">
                            <th className="text-left text-xs select-none cursor-pointer"
                                onClick={() => sortHandler('language')}>
                                Lang.
                                {currentSort.current.column === 'language' ? (
                                    <Icon iconNode={sortIcon} className="h-3 inline"/>) : ''}
                            </th>
                            <th scope="col" className="text-left select-none cursor-pointer"
                                onClick={() => sortHandler('title')}>
                                Title
                                {currentSort.current.column === 'title' ? (
                                    <Icon iconNode={sortIcon} className="h-3 inline"/>) : ''}
                            </th>
                            <th scope="col" className="text-left select-none cursor-pointer"
                                onClick={() => sortHandler('act_name')}>
                                Act
                                {currentSort.current.column === 'act_name' ? (
                                    <Icon iconNode={sortIcon} className="h-3 inline"/>) : ''}
                            </th>
                            <th scope="col" className="text-right select-none cursor-pointer"
                                onClick={() => sortHandler('play_count')}>
                                Play count
                                {currentSort.current.column === 'play_count' ? (
                                    <Icon iconNode={sortIcon} className="h-3 inline"/>) : ''}
                            </th>
                            <th/>
                            <th/>
                        </tr>
                        </thead>
                        <tbody>
                        {songs.data.map((song) => (
                            <tr className="hover-bg select-none" key={song.id}>
                                <th scope="row" className="text-center">
                                    <LanguageFlag languageCode={song.language}/>
                                </th>
                                <th scope="row" className="font-bold text-left">{song.title}</th>
                                <td className="text-left">{song.act.name}</td>
                                <td className="text-sm text-right">{song.play_count.toLocaleString()}</td>
                                <td className="text-sm text-center">
                                    {song.url && <a href={song.url} target="_blank">
                                        <Music/>
                                    </a>}
                                </td>
                                <td>
                                    <div className="toolbar">
                                        <Button variant="secondary"
                                                onClick={() => editHandler(song)}
                                                title="Edit Song">
                                            <Edit className="h-3 w-3"/>
                                        </Button>
                                        <Button variant="destructive"
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
                </div>) : (
                <Nothing>
                    No Songs defined.
                </Nothing>
            )}

            <Pagination results={songs} onPageChange={pageHandler}/>

            <SongDialog song={currentSong} acts={acts} open={isEditDialogOpen}
                        onOpenChange={() => setIsEditDialogOpen(false)}/>
            <DestructiveDialog open={isDeleteDialogOpen} onOpenChange={() => setIsDeleteDialogOpen(false)}
                               onConfirm={confirmDeleteHandler} processing={isDeleting}>
                <DialogTitle>{`Delete Song "${currentSong?.title}" by ${currentSong?.act.name}`}</DialogTitle>

                <span className="italic">This will remove the Song from all Rounds.</span><br/>
                Are you sure you want to do this?
            </DestructiveDialog>
        </AppLayout>
    );
}
