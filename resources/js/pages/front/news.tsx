import { Head, Link } from '@inertiajs/react';
import { FrontContent } from '@/components/front/front-content';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/mode/advert';
import { NewsPost, PaginatedResponse } from '@/types';
import AboutBanner from '@/components/front/about-banner';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { NewspaperIcon } from 'lucide-react';
import HeadingSmall from '@/components/heading-small';

interface NewsPageProps {
    posts: PaginatedResponse<NewsPost>;
}

const NewsPage: React.FC<NewsPageProps> = ({ posts }) => {

    return (
        <>
            <Head title="Contest News">
                <meta name="description"
                      content="Stay updated with the latest news from the CATAWOL Records Song Contest — announcements, round results, artist highlights, and behind-the-scenes stories."/>
            </Head>

            <FrontContent>
                <div className="flex flex-col lg:flex-row gap-5">

                    <section className="lg:w-4/5">
                        <PlaceholderPattern className="w-full stroke-neutral-300 mb-5"/>

                        <h1 className="font-display text-2xl mb-5">Contest News</h1>

                        {/* TODO pagination. */}

                        <div className="flex flex-col gap-3">
                            {posts.data.map((post) => (
                                <article key={post.id} className={"flex border"}>
                                    <Link href={post.url} className="flex hover-bg hover:shadow-md">
                                        <div className="w-1/8 flex items-center justify-center bg-white">
                                            <NewspaperIcon className="w-12"/>
                                        </div>
                                        <div className="w-7/8 flex flex-col items-start justify-center p-5">
                                            <h2 className="font-display text-xl leading-tight">{post.title}</h2>
                                            <p className="text-sm leading-tight">{post.excerpt}</p>
                                        </div>
                                    </Link>
                                </article>
                            ))}
                        </div>

                        <Advert className="mt-3" height={240}/>
                    </section>

                    <aside className="lg:w-1/5 flex flex-col gap-5">
                        <Advert height={200}/>

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

NewsPage.layout = (page) => <FrontLayout>{page}</FrontLayout>;

export default NewsPage;
