interface Props {
    title: string,
    children?: React.ReactNode
}

export const AdminHeader: React.FC<Props> = ({ title, children }) => {

    return (
        <div className="admin-header">
            <h1 className="admin-header-title">{title}</h1>
            <div className="admin-header-controls">
                {children}
            </div>
        </div>
    )
}
