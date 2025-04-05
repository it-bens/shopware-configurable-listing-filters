const fs = require('fs');
const path = require('path');

const shopwareTypesDir = path.resolve(__dirname, '../../../../vendor/shopware/administration/Resources/app/administration/src');
const targetDir = path.resolve(__dirname, '../node_modules/@shopware');

if (!fs.existsSync(targetDir)) {
    fs.mkdirSync(targetDir, { recursive: true });
}

if (!fs.existsSync(path.resolve(targetDir, 'administration'))) {
    try {
        const relativePath = path.relative(targetDir, shopwareTypesDir);

        if (process.platform === 'win32') {
            fs.symlinkSync(relativePath, path.resolve(targetDir, 'administration'), 'junction');
        } else {
            fs.symlinkSync(relativePath, path.resolve(targetDir, 'administration'), 'dir');
        }
        console.log('Symlink for Shopware Administration created successfully.');
    } catch (err) {
        console.error('Error creating symlink:', err);
    }
}
