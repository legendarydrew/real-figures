import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/mode/advert';
import { NewsPost } from '@/types';
import AboutBanner from '@/components/front/about-banner';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import TextLink from '@/components/mode/text-link';
import HeadingSmall from '@/components/heading-small';

interface NewsPostPageProps {
    post: NewsPost;
}

const NewsPostPage: React.FC<NewsPostPageProps> = ({ post }) => {

    return (
        <>
            <Head title={post.title}>
                <meta name="description" content={post.excerpt}/>
            </Head>

            <FrontContent>
                <div className="flex flex-col lg:flex-row gap-5">

                    <article className="lg:w-4/5">
                        <PlaceholderPattern className="w-full stroke-neutral-300 mb-5"/>

                        <header className="mb-5">
                            <h1 className="font-display text-3xl">{post.title}</h1>
                            {post.published_at && (
                                <p className="text-muted-foreground text-sm">CATAWOL
                                    Records, <time>{post.published_at}</time></p>)}
                        </header>

                        <div className="content text-base pr-10" dangerouslySetInnerHTML={{ __html: post.content }}/>

                        <p className="text-muted-foreground text-sm p-3">
                            You are welcome to get in touch with us through our <TextLink
                            href={route('contact')}>Contact page</TextLink>.<br/>
                        </p>

                        {/* TODO links to previous and next News Posts. */}

                        <Advert className="mt-3" height={240}/>
                    </article>

                    <aside className="lg:w-1/5 flex flex-col gap-5">
                        {/* TODO other News Posts. */}

                        <Advert className="mt-3" height={160}/>

                        <div className="text-sm leading-tight">
                            <HeadingSmall title="About CATAWOL Records"/>
                            <p>CATAWOL Records is a music label championing bold voices and meaningful messages. We
                                support artists who use creativity to challenge norms, raise awareness, and connect
                                through sound.</p>
                        </div>

                        <PlaceholderPattern className="w-full stroke-zinc-300" title="SilentMode banner"/>
                        <PlaceholderPattern className="w-full stroke-red-300" title="Brick Therapy banner"/>

                    </aside>

                </div>

            </FrontContent>

            <AboutBanner/>
        </>
    )
}

NewsPostPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default NewsPostPage;
