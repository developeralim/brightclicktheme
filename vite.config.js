import { defineConfig } from "vite";
import { resolve } from "path";
import { mkdirSync, writeFileSync, rmSync } from "fs";

// Writes dist/.vite/hot while the dev server runs so PHP (IS_DEV) can detect it.
const hotFilePlugin = () => {
    const hotFile = resolve(__dirname, "dist/.vite/hot");
    return {
        name: "wp-hot-file",
        apply: "serve",
        configureServer(server) {
            mkdirSync(resolve(__dirname, "dist/.vite"), { recursive: true });
            writeFileSync(hotFile, "http://localhost:5173");
            const clean = () => {
                try {
                    rmSync(hotFile);
                } catch {}
            };
            server.httpServer?.once("close", clean);
            process.on("SIGINT", () => {
                clean();
                process.exit();
            });
            process.on("SIGTERM", () => {
                clean();
                process.exit();
            });
        },
    };
};

export default defineConfig({
    base: "",

    plugins: [hotFilePlugin()],

    server: {
        host: "localhost",
        port: 5173,
        strictPort: true,
        cors: true,
    },

    build: {
        outDir: "dist",
        emptyOutDir: true,
        manifest: true,

        rollupOptions: {
            input: {
                main: resolve(__dirname, "assets/js/main.js"),
                editor: resolve(__dirname, "assets/js/editor.js"),
            },
            output: {
                entryFileNames: "js/[name]-[hash].js",
                chunkFileNames: "js/[name]-[hash].js",
                assetFileNames: ({ name }) => {
                    if (/\.(css)$/.test(name ?? "")) return "css/[name]-[hash][extname]";
                    if (/\.(png|jpe?g|gif|svg|webp|ico)$/.test(name ?? ""))
                        return "images/[name]-[hash][extname]";
                    if (/\.(woff2?|eot|ttf|otf)$/.test(name ?? ""))
                        return "fonts/[name]-[hash][extname]";
                    return "assets/[name]-[hash][extname]";
                },
            },
        },
    },

    css: {
        postcss: "./postcss.config.js",
    },
});
