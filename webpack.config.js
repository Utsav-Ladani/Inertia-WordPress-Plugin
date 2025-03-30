import defaultConfig from '@wordpress/scripts/config/webpack.config.js';
import AssetCleanerPlugin from './webpack-plugins/asset-cleaner-plugin.js';

export default {
    ...defaultConfig,
    plugins: [
        ...defaultConfig.plugins,
        new AssetCleanerPlugin(),
    ],
};
