import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger } from '@/components/ui/select';
import React, { useState } from 'react';

interface Props {
    posts: { id: number, title: string, published_at: string }[];
    onChange: (id: number) => void;
}

export const NewsPostSelect: React.FC<Props> = ({
                                                    posts, onChange = (id) => {
    }
                                                }) => {

    const [selected, setSelected] = useState<number>();

    const postLabel = (stageId: number) => {
        const matchingPost = posts.find((stage) => stage.id == stageId);
        return matchingPost ? (<span>
            {matchingPost.published_at} &mdash; {matchingPost.title}
        </span>) : 'none';
    };

    const selectHandler = (id: number): void => {
        setSelected(id);
        onChange(id);
    };

    return (
        <section>
            <Label htmlFor="postPrevious">Reference to a previous News Post (optional)</Label>
            <Select id="postPrevious" onValueChange={selectHandler} disabled={!posts.length}>
                <SelectTrigger>
                    {selected ? postLabel(selected) : <i>none</i>}
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value={0}>
                        <i>none</i>
                    </SelectItem>
                    {posts?.map((post) => (
                        <SelectItem key={post.id} value={post.id}>
                            {postLabel(post.id)}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
        </section>
    );
};
