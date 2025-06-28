import { Head, Link } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/mode/advert';
import { NewsPost, PaginatedResponse } from '@/types';
import AboutBanner from '@/components/front/about-banner';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import Heading from '@/components/heading';

interface NewsPageProps {
    posts: PaginatedResponse<NewsPost>;
}

const NewsPage: React.FC<NewsPageProps> = ({ posts }) => {

    return (
        <>
            <Head title="Contest News">
                <meta name="description" content="News about the Contest."/>
            </Head>

            <FrontContent>
                <div className="flex flex-col lg:flex-row gap-5">

                    <section className="lg:w-4/5">
                        <PlaceholderPattern className="w-full stroke-neutral-300 mb-5"/>

                        <Heading title="Contest News"/>
                        ...

                        {posts.data.map((post) => (
                            <article key={post.id}>
                                <Link href={post.url}>{post.title}</Link>
                            </article>
                        ))}

                        <Advert className="mt-3" height={240}/>
                    </section>

                    <aside className="lg:w-1/5">
                        <Advert/>
                    </aside>

                </div>

            </FrontContent>

            <AboutBanner/>
        </>
    )
}

NewsPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default NewsPage;
