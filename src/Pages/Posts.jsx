import { Link } from '@inertiajs/react';

export default function Posts({ posts, homeURL }) {
    return (
        <div>
            <h1>Posts</h1>
            <p>This is a posts page</p>
            <Link href={homeURL}>Home</Link>
            <PostsList posts={posts} />
        </div>
    );
}

function PostsList({ posts }) {
    return (
        <ul className="posts">
            {posts.map((post) => (
                <Post key={post.ID} post={post} />
            ))}
        </ul>
    );
}

function Post({ post }) {
    console.log(post);
    return (
        <li className="post">
            <div className="col">
                <h3 className="post__title">{post.post_title}</h3>
                <span className="post__date">{post.post_date}</span>
            </div>
        </li>
    );
}
