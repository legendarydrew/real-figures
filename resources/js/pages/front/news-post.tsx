import { Head, Link } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/mode/advert';
import { NewsPost } from '@/types';
import AboutBanner from '@/components/front/about-banner';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import TextLink from '@/components/mode/text-link';
import HeadingSmall from '@/components/heading-small';
import { ChevronLeft, ChevronRight, NewspaperIcon } from 'lucide-react';
import { BrickTherapyLink } from '@/components/front/brick-therapy-link';

interface NewsPostPageProps {
    post: NewsPost;
}

const NewsPostPage: React.FC<NewsPostPageProps> = ({ post }) => {

    return (
        <>
            <Head title={post.title}>
                <meta name="description" content={post.excerpt}/>
                {post.pages?.previous && (<link rel="prev" href={post.pages.previous.url}/>)}
                {post.pages?.next && (<link rel="next" href={post.pages.next.url}/>)}
            </Head>

            <FrontContent>
                <div className="flex flex-col lg:flex-row gap-5">

                    <article className="lg:w-4/5">
                        <PlaceholderPattern className="w-full stroke-neutral-300 mb-5"/>

                        <header className="mb-5">
                            <h1 className="font-display text-3xl leading-tight">{post.title}</h1>
                            {post.published_at && (
                                <p className="text-muted-foreground text-sm">CATAWOL
                                    Records, <time>{post.published_at}</time></p>)}
                        </header>

                        <div className="content text-base pr-10" dangerouslySetInnerHTML={{ __html: post.content }}/>

                        <p className="text-muted-foreground text-sm p-3 my-5">
                            You are welcome to get in touch with us through our <TextLink
                            href={route('contact')}>Contact page</TextLink>.<br/>
                        </p>

                        {(post.pages?.previous || post.pages?.next) && (<hr className="my-5"/>)}

                        <div className="flex flex-col lg:flex-row gap-3 justify-between">
                            {post.pages.previous && (
                                <Link className="lg:w-2/5 hover-bg p-5 mr-auto flex flex-col lg:justify-end gap-1"
                                      href={post.pages.previous.url} rel="prev">
                                    <span
                                        className="font-display text-xl leading-tight">{post.pages.previous.title}</span>
                                    <b className="text-sm flex items-center"><ChevronLeft className="w-4 h-4"/> Previous
                                        post</b>
                                </Link>)}
                            {post.pages.next && (
                                <Link
                                    className="lg:w-2/5 md:text-right hover-bg p-5 ml-auto flex flex-col lg:justify-end md:items-end gap-1"
                                    href={post.pages.next.url}
                                    rel="next">
                                    <span className="font-display text-xl leading-tight">{post.pages.next.title}</span>
                                    <b className="text-sm flex items-center">Next post <ChevronRight
                                        className="w-4 h-4"/></b>
                                </Link>)}
                        </div>

                        <Advert className="mt-3" height={240}/>
                    </article>

                    <aside className="lg:w-1/5 flex flex-col gap-5">

                        <Advert height={200}/>

                        {post.pages?.others?.length && (
                            <menu className="flex flex-col gap-1">
                                <HeadingSmall title="More press releases"/>
                                {post.pages?.others.map((row) => (
                                    <Link key={row.url}
                                          className="flex gap-3 hover-bg p-2 text-sm font-display leading-tight"
                                          href={row.url}>
                                        <NewspaperIcon className="w-4 flex-shrink-0"/>
                                        {row.title}
                                    </Link>
                                ))}
                            </menu>
                        )}

                        <div className="text-sm leading-tight">
                            <HeadingSmall title="About CATAWOL Records"/>
                            <p>CATAWOL Records is a music label championing bold voices and meaningful messages. We
                                support artists who use creativity to challenge norms, raise awareness, and connect
                                through sound.</p>
                        </div>

                        <div className="flex flex-row max-h-[8rem] lg:max-h-none lg:flex-col gap-3">
                            <PlaceholderPattern className="w-[8rem] lg:w-auto flex-shrink-0 stroke-zinc-300"
                                                title="SilentMode banner"/>
                            <BrickTherapyLink className="max-h-[8rem] lg:max-h-none lg:flex-shrink-0"/>
                        </div>

                    </aside>

                </div>

            </FrontContent>

            <AboutBanner/>
        </>
    )
}

NewsPostPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default NewsPostPage;
