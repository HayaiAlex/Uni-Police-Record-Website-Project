let root = 'http://'
let path = import.meta.url;
path = path.split("config.js")[0]
console.log(`Path: ${path}`)

const roots = {
    'http://localhost:5173/src/': 'http://localhost',
    'http://mersey.cs.nott.ac.uk/~psxaa48/police/frontend/src/': 'http://mersey.cs.nott.ac.uk/~psxaa48/police'
}

if (roots[path]) {
    root = roots[path]
} else {
    console.log("Did not find a custom root to use. Using default localhost to get php files")
    root = 'http://localhost'
}
console.log(`Root: ${root}`)
export { root }