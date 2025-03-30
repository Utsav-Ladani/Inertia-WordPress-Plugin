import { Link } from '@inertiajs/react';
import User from '../Components/User';

export default function Home({ pages, user }) {
    return (
        <div>
            <h1>Home</h1>
            <p>This is the home page</p>
            {pages.map((page) => (
                <Link key={page.name} href={page.url}>{page.name}</Link>
            ))}
            <User user={user} />
        </div>
    );
}
