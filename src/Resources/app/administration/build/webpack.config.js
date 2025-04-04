const { join } = require('path');

module.exports = (params) => {
    return {
        resolve: {
            modules: [
                `${params.basePath}/Resources/app/storefront/node_modules`,
            ],
        },
        module: {
            rules: [
                {
                    test: /\.ts$/,
                    loader: 'babel-loader',
                    options: {
                        presets: [join(__dirname, '..', 'node_modules', '@babel', 'preset-typescript')]
                    }
                }
            ]
        },
    };
}