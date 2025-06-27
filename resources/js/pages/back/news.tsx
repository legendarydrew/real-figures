import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import React, { RefObject, useRef, useState } from 'react';
import { Edit, Trash } from 'lucide-react';
import { NewsPost, PaginatedResponse } from '@/types';
import { Pagination } from '@/components/admin/pagination';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import { DialogTitle } from '@/components/ui/dialog';
import { Toaster } from '@/components/mode/toast-message';
import { Nothing } from '@/components/mode/nothing';

export default function NewsPage({ posts }: Readonly<{ posts: PaginatedResponse<NewsPost> }>) {

    // Using refs instead of states, as these do not require re-rendering the page.
    // However, things will look just a little more stiff.
    const currentPage: RefObject<number> = useRef(posts?.meta.pagination.current_page ?? 1);

    const [currentPost, setCurrentPost] = useState<NewsPost>();
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState<boolean>(false);
    const [isDeleting, setIsDeleting] = useState<boolean>(false);

    const pageHandler = (pageNumber: number): void => {
        currentPage.current = pageNumber;
        fetchSongs();
    };

    const fetchSongs = (): void => {
        router.reload({
            data: {
                page: currentPage.current
            },
            only: ['songs'],
            showProgress: true,
            onSuccess: (response) => {
                currentPage.current = response.props.songs.meta.pagination.current_page;
            }
        });
    }

    const editHandler = (post: NewsPost): void => {

    }

    const deleteHandler = (post: NewsPost): void => {
        setCurrentPost(post);
        setIsDeleteDialogOpen(true);
    }

    const confirmDeleteHandler = () => {
        if (currentPost) {
            router.delete(route('news.destroy', { id: currentPost.id }), {
                preserveUrl: true,
                preserveScroll: true,
                onStart: () => {
                    setIsDeleting(true);
                },
                onFinish: () => {
                    setIsDeleting(false);
                },
                onSuccess: () => {
                    Toaster.success(`"${currentPost.title}" was deleted.`);
                    setIsDeleteDialogOpen(false);
                    setCurrentPost(undefined);
                }
            });
        }
    };

    return (
        <AppLayout>
            <Head title="News / Press Releases"/>

            <div className="flex mb-3 p-4">
                <h1 className="display-text flex-grow text-2xl">News</h1>
                <div className="flex gap-1">
                    <Button variant="default">Create Post</Button>
                    <Button asChild variant="secondary">
                        <Link href={route('admin.news-generate.index')}>Generate</Link>
                    </Button>
                </div>
            </div>

            {posts.meta.pagination.total ? (
                <div className="overflow-x-auto">
                    <table className="dashboard-table">
                        <colgroup>
                            <col/>
                            <col style={{ width: '10em' }}/>
                            <col style={{ width: '10em' }}/>
                            <col style={{ width: '6em' }}/>
                        </colgroup>
                        <thead className="text-sm">
                        <tr className="border-b-2">
                            <th>&nbsp;</th>
                            <th scope="col" className="text-center select-none">
                                Published at
                            </th>
                            <th scope="col" className="text-center select-none">
                                Updated at
                            </th>
                            <th/>
                        </tr>
                        </thead>
                        <tbody>
                        {posts.data.map((post) => (
                            <tr className="hover-bg select-none" key={post.id}>
                                <th scope="row" className="text-left">
                                    <b>{post.title}</b><br/>
                                    {post.excerpt && <small
                                        className="block font-normal text-wrap leading-tight">{post.excerpt}</small>}
                                </th>
                                <td className="text-center">{post.published_at ?? '-'}</td>
                                <td className="text-center">{post.updated_at}</td>
                                <td>
                                    <div className="toolbar">
                                        <Button variant="secondary"
                                                onClick={() => editHandler(post)}
                                                title="Edit Post">
                                            <Edit className="h-3 w-3"/>
                                        </Button>
                                        <Button variant="destructive"
                                                onClick={() => deleteHandler(post)}
                                                title="Delete Post">
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
                    No News Posts have been created.
                </Nothing>
            )}

            <Pagination results={posts} onPageChange={pageHandler}/>

            <DestructiveDialog open={isDeleteDialogOpen} onOpenChange={() => setIsDeleteDialogOpen(false)}
                               onConfirm={confirmDeleteHandler} processing={isDeleting}>
                <DialogTitle>{"Delete News Post"}</DialogTitle>

                <span className="italic">This will remove the News Post titled <b>{currentPost?.title}</b>.</span><br/>
                Are you sure you want to do this?
            </DestructiveDialog>
        </AppLayout>
    );
}
