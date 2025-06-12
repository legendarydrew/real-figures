import React, { ChangeEvent, useEffect, useState } from "react";
import { CardContent } from "@/components/ui/card";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { marked } from "marked";
import {
    BoldIcon,
    CodeIcon,
    HeadingIcon,
    ItalicIcon,
    LinkIcon,
    ListIcon,
    ListOrderedIcon,
    QuoteIcon,
    ViewIcon
} from 'lucide-react';
import { Toggle } from '@/components/ui/toggle';
import { cn } from '@/lib/utils';

/**
 * Markdown editor component
 * Generated using ChatGPT, modified by me.
 * Why not use an existing package? Because I wanted control over its appearance and functionality.
 */


interface MarkdownEditorProps {
    value: string;
    className?: string;
    disabled?: boolean;
    placeholder?: string;
    onChange: (value: string) => void;
}

export const MarkdownEditor: React.FC<MarkdownEditorProps> = ({
                                                                  value,
                                                                  className,
                                                                  disabled,
                                                                  placeholder,
                                                                  onChange
                                                              }) => {
    const [markdown, setMarkdown] = useState(value);
    const [preview, setPreview] = useState<boolean>(false);

    const applyFormatting = (syntax) => {
        const textarea: HTMLTextAreaElement = document.getElementById("markdown-textarea");
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = markdown.slice(start, end);
        let newText = markdown;

        switch (syntax) {
            case "bold":
                newText = markdown.slice(0, start) + `**${selectedText}**` + markdown.slice(end);
                break;
            case "italic":
                newText = markdown.slice(0, start) + `*${selectedText}*` + markdown.slice(end);
                break;
            case "code":
                newText = markdown.slice(0, start) + `\`${selectedText}\`` + markdown.slice(end);
                break;
            case "heading":
                newText = markdown.slice(0, start) + `# ${selectedText}` + markdown.slice(end);
                break;
            case "link":
                newText = markdown.slice(0, start) + `[${selectedText}](url)` + markdown.slice(end);
                break;
            case "ulist":
                newText = markdown.slice(0, start) + `- ${selectedText}` + markdown.slice(end);
                break;
            case "olist":
                newText = markdown.slice(0, start) + `1. ${selectedText}` + markdown.slice(end);
                break;
            case "quote":
                newText = markdown.slice(0, start) + `> ${selectedText}` + markdown.slice(end);
                break;
            default:
                break;
        }
        setMarkdown(newText);
    };

    useEffect(() => {
        const handleKeyDown = (e) => {
            if (e.metaKey || e.ctrlKey) {
                const keyMap = {
                    b: "bold",
                    i: "italic",
                    k: "link",
                    h: "heading",
                    '`': "code",
                    l: "ulist",
                    o: "olist",
                    q: "quote"
                };
                const syntax = keyMap[e.key.toLowerCase()];
                if (syntax) {
                    e.preventDefault();
                    applyFormatting(syntax);
                }
            }
        };

        document.addEventListener("keydown", handleKeyDown);
        return () => document.removeEventListener("keydown", handleKeyDown);
    }, [markdown]);

    useEffect(() => {
        setMarkdown(value);
    }, [value]);

    const changeHandler = (e: ChangeEvent) => {
        setMarkdown(e.target.value);
        onChange(e.target.value);
    };

    return (
        <>
            {/* Toolbar. */}
            <div className="flex justify-between mb-1">

                <div className="flex gap-1">
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("bold")} tabIndex="-1">
                        <BoldIcon className="h-2"/>
                    </Button>
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("italic")} tabIndex="-1">
                        <ItalicIcon className="h-2"/>
                    </Button>
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("code")} tabIndex="-1">
                        <CodeIcon className="h-2"/>
                    </Button>
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("heading")} tabIndex="-1">
                        <HeadingIcon className="h-2"/>
                    </Button>
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("link")} tabIndex="-1">
                        <LinkIcon className="h-2"/>
                    </Button>
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("ulist")} tabIndex="-1">
                        <ListIcon className="h-2"/>
                    </Button>
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("olist")} tabIndex="-1">
                        <ListOrderedIcon className="h-2"/>
                    </Button>
                    <Button className="p-1" variant="outline" type="button" size="sm"
                            onClick={() => applyFormatting("quote")} tabIndex="-1">
                        <QuoteIcon className="h-2"/>
                    </Button>
                </div>

                <div>
                    <Toggle className="p-1" size="sm" value={preview} onClick={() => setPreview(!preview)}
                            title="Toggle preview">
                        <ViewIcon className="h-2"/>
                    </Toggle>
                </div>
            </div>

            {/* Content area. */}
            {preview ? (
                <CardContent className="p-2 text-base prose max-w-none">
                    <div dangerouslySetInnerHTML={{ __html: marked(markdown) }}/>
                </CardContent>

            ) : (
                <Textarea
                    id="markdown-textarea"
                    placeholder={placeholder}
                    disabled={disabled}
                    value={markdown}
                    onChange={changeHandler}
                    className={cn("min-h-[8rem] font-mono", className)}
                />
            )}
        </>
    );
}
