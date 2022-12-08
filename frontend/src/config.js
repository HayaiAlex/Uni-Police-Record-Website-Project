// Root is used as a prefix by the front end in fetch() calls to the php backend
let root = 'http://'
let path = import.meta.url;
path = path.split("config.js")[0]
console.log(`Path: ${path}`)

const roots = {
    'http://localhost:5173/src/': 'http://localhost',
    'http://mersey.cs.nott.ac.uk/~psxaa48/police/main.js': 'http://mersey.cs.nott.ac.uk/~psxaa48/police'
}

if (roots[path]) {
    root = roots[path]
} else {
    console.log("Did not find a custom root to use. Using default localhost to get php files")
    root = 'http://localhost'
}
console.log(`Root: ${root}`)

// Route is used by the router to get a url prefix for each host
let route = ''
const routes = {
    'http://localhost:5173/src/': '',
    'http://mersey.cs.nott.ac.uk/~psxaa48/police/main.js': '/~psxaa48/police'
}
if (routes[path]) {
    routes = routes[path]
} else {
    console.log("Did not find a custom url prefix to use. Defaulting to /")
}
export { root, route }