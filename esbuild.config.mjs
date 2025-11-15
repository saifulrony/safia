import { build, context } from 'esbuild';
import { join } from 'node:path';

const rootDir = process.cwd();
const srcDir = join(rootDir, 'wp-content/plugins/probuilder/assets/js/src');
const outFile = join(rootDir, 'wp-content/plugins/probuilder/assets/js/editor.js');

const options = {
    entryPoints: [join(srcDir, 'index.js')],
    bundle: true,
    outfile: outFile,
    format: 'iife',
    target: ['es2018'],
    sourcemap: true,
    logLevel: 'info',
    banner: {
        js: '/* This file is auto-generated via esbuild. Edit files under assets/js/src instead. */',
    },
};

if (process.argv.includes('--watch')) {
    const ctx = await context(options);
    await ctx.watch();
    console.log('👀 Watching ProBuilder editor sources with esbuild...');
} else {
    await build(options);
    console.log('✅ ProBuilder editor bundle generated via esbuild.');
}

