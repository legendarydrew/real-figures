import { Label } from '@/components/ui/label';
import React, { useState } from 'react';
import { Checkbox } from '@/components/ui/checkbox';
import { Subscriber } from '@/types';

interface Props {
    posts: { id: number, type: string, title: string, published: string }[];
    onChange: (id: number[]) => void;
}

export const NewsPostSelect: React.FC<Props> = ({
                                                    posts, onChange = (id) => {
    }
                                                }) => {

    const [selectedIds, setSelectedIds] = useState<number[]>([]);

    const selectHandler = (subscriber: Subscriber): void => {
        const updated = [...new Set([...selectedIds, subscriber.id])];
        setSelectedIds(updated);
        onChange(updated);
    }

    const deselectHandler = (subscriber: Subscriber): void => {
        const updated = selectedIds.filter((id) => id !== subscriber.id);
        setSelectedIds(updated);
        onChange(updated);
    }

    return posts?.length ? (
        <section>
            <Label>News Post references <span className="text-muted-foreground">(optional)</span></Label>
            <ul className="border max-h-48 overflow-y-auto">
                {posts?.map((post) => (
                    <li key={post.id}
                        className="hover-bg flex items-center gap-2 py-1 px-2 select-none">
                        <Checkbox id={`post-${post.id}`} className="bg-white"
                                  checked={selectedIds.includes(post.id)}
                                  onCheckedChange={(state) => state ? selectHandler(post) : deselectHandler(post)}/>
                        <Label htmlFor={`post-${post.id}`}
                               className="flex-grow truncate font-semibold select-none">
                            <span className="font-mono">[{post.published}]</span> [{post.type}] {post.title}
                        </Label>
                    </li>
                ))}
            </ul>
        </section>
    ) : '';
};
