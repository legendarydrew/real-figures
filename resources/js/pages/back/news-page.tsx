import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import React, { RefObject, useRef, useState } from 'react';
import { Edit, MicrochipIcon, NewspaperIcon, Trash } from 'lucide-react';
import { NewsPost, PaginatedResponse } from '@/types';
import { Pagination } from '@/components/admin/pagination';
import { DestructiveDialog } from '@/components/admin/destructive-dialog';
import { DialogTitle } from '@/components/ui/dialog';
import { RTToast } from '@/components/mode/toast-message';
import { Nothing } from '@/components/mode/nothing';
import { Badge } from '@/components/ui/badge';
import { AdminHeader } from '@/components/admin/admin-header';

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
        router.visit(route('admin.news.edit', { id: post.id }));
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
                    RTToast.success(`"${currentPost.title}" was deleted.`);
                    setIsDeleteDialogOpen(false);
                    setCurrentPost(undefined);
                }
            });
        }
    };

    return (
        <AppLayout>
            <Head title="News / Press Releases"/>

            <div className="admin-content">

                <AdminHeader title="News">
                    <Button asChild variant="default">
                        <Link href={route('admin.news.create')}>Create Post</Link>
                    </Button>
                    <Button asChild variant="secondary">
                        <Link href={route('admin.news-generate')}>
                            <MicrochipIcon className="size-4" /> Generate
                        </Link>
                    </Button>
                </AdminHeader>

                {posts.meta.pagination.total ? (
                    <div className="overflow-x-auto">
                        <table className="admin-table">
                            <thead>
                            <tr>
                                <th scope="col" />
                                <th scope="col">Status</th>
                                <th scope="col">Published</th>
                                <th scope="col">Updated</th>
                                <th scope="col"/>
                            </tr>
                            </thead>
                            <tbody>
                            {posts.data.map((post) => (
                                <tr key={post.id}>
                                    <th scope="row" className="text-left">
                                        <h2 className="display-text text-base mb-1">{post.title}</h2>
                                        {post.excerpt && <div
                                            className="font-normal text-sm text-wrap leading-tight">{post.excerpt}</div>}
                                    </th>
                                    <td className="text-center">
                                        {post.published_at ? (<Badge variant="affirmative">Published</Badge>) : (<Badge variant="secondary">Draft</Badge>)}
                                    </td>
                                    <td className="text-center">{post.published_at ?? '-'}</td>
                                    <td className="text-center">{post.updated_at}</td>
                                    <td>
                                        <div className="toolbar">
                                            <Button asChild size="sm" variant="outline" className="p-2" title="View Post">
                                                <Link href={post.url}>
                                                    <NewspaperIcon className="size-4"/>
                                                </Link>
                                            </Button>
                                            <Button variant="secondary" size="sm" className="p-2"
                                                    onClick={() => editHandler(post)}
                                                    title="Edit Post">
                                                <Edit className="size-4"/>
                                            </Button>
                                            <Button variant="destructive" size="sm" className="p-2"
                                                    onClick={() => deleteHandler(post)}
                                                    title="Delete Post">
                                                <Trash className="size-4"/>
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
            </div>

            <DestructiveDialog open={isDeleteDialogOpen} onOpenChange={() => setIsDeleteDialogOpen(false)}
                               onConfirm={confirmDeleteHandler} processing={isDeleting}>
                <DialogTitle>{"Delete News Post"}</DialogTitle>

                This will remove the News Post titled <b>{currentPost?.title}</b>.<br/>
                Are you sure you want to do this?
            </DestructiveDialog>
        </AppLayout>
    );
}
