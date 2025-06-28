import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/mode/advert';
import { NewsPost } from '@/types';
import AboutBanner from '@/components/front/about-banner';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';

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

                        <Advert className="mt-3" height={240}/>
                    </article>

                    <aside className="lg:w-1/5">
                        ...
                        <Advert className="mt-3" height={160}/>
                    </aside>

                </div>

            </FrontContent>

            <AboutBanner/>
        </>
    )
}

NewsPostPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default NewsPostPage;
