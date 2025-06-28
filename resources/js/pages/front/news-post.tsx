import { Head } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import Heading from '@/components/heading';
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

                    <div className="lg:w-4/5 p-3">
                        <PlaceholderPattern className="w-full stroke-neutral-300 mb-5"/>
                        <Heading title={post.title} description={post.published_at}/>

                        <div className="content text-base">
                            {post.content}
                        </div>

                        <Advert className="mt-3" height={160}/>
                    </div>

                    <div className="lg:w-1/5">
                        ...
                    </div>

                </div>

            </FrontContent>

            <AboutBanner/>
        </>
    )
}

NewsPostPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default NewsPostPage;
