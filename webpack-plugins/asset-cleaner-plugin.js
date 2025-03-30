import fs from 'fs';
import path from 'path';

export default class AssetCleanerPlugin {
    currentAssets = [];

    apply(compiler) {
        compiler.hooks.done.tap('asset-cleaner-plugin', ({ compilation }) => {
            const assetList = Object.keys(compilation.assets);

            const staleFiles = this.currentAssets.filter((previousAsset) => 
                !assetList.includes(previousAsset)
            );
    
            this.currentAssets = assetList;
    
            staleFiles.forEach((file) => {
                fs.unlinkSync(path.join(compilation.options.output.path, file));
            });
        });
    }
}
