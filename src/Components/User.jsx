export default function User({ user }) {
    return (
        <div className="user">
            <img className="user__avatar" src={user.avatar} alt={user.name} />
            <h1 className="user__name">{user.name}</h1>
            <p className="user__email">{user.email}</p>
        </div>
    );
}