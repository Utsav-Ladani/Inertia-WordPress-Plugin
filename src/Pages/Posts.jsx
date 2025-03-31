import { Link } from '@inertiajs/react';

export default function Posts({ posts, homeURL }) {
    return (
        <div>
            <h1>Posts</h1>
            <p>This is a posts page</p>
            <Link href={homeURL}>Back to Home</Link>
            <PostsList posts={posts} />
        </div>
    );
}

function PostsList({ posts }) {
    return (
        <ul className="posts">
            {posts.map((post) => (
                <Post key={post.id} post={post} />
            ))}
        </ul>
    );
}

function Post({ post }) {
    console.log(post);
    return (
        <li className="post hover:bg-blue-500 hover:text-white">
            <div className="col">
                <h3 className="post__title">{post.title}</h3>
                <span className="post__date">{post.date}</span>
            </div>
        </li>
    );
}
