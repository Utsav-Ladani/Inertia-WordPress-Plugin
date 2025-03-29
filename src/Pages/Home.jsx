import { Link } from '@inertiajs/react';

export default function Home({ pages }) {
    console.log(pages);
    return (
        <div>
            <h1>Home</h1>
            <p>This is the home page</p>
            {pages.map((page) => (
                <Link key={page.name} href={page.url}>{page.name}</Link>
            ))}
        </div>
    );
}
