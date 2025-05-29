import Heading from '@/components/heading';
import FrontLayout from '@/layouts/front-layout';
import { Advert } from '@/components/advert';

export default function ErrorPage({ status }) {
    const title = {
        403: '403: Forbidden',
        404: '404: Page Not Found',
        500: '500: Server Error',
        503: '503: Service Unavailable'
    }[status]

    const description = {
        403: 'You do not have access to this page.',
        404: 'The page you are looking for could not be found.',
        500: 'Something has gone wrong on our servers.',
        503: 'This site is currently under maintenance. Please check back soon.'
    }[status]

    return (
        <FrontLayout>
            <div className="h-full flex flex-col items-center justify-center text-center">
                <Heading title={title} description={description}/>

                <Advert className="mx-auto" height={160} />
            </div>
        </FrontLayout>
    )
}
