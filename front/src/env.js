const env = {
    url: import.meta.env.VITE_URL || 'http://localhost:8000/',
    api: import.meta.env.VITE_API || 'http://localhost:8000/api/v1/',
    title: import.meta.env.VITE_TITLE || 'Libertas',
}

export default env;
