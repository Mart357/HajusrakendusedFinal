<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/Mart357/HajusrakendusedFinal.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('ta23pors.itmajakas.ee')
    ->set('remote_user', 'virt123060')
    ->set('deploy_path', '~/HajusrakendusedFinal');

// Hooks

after('deploy:failed', 'deploy:unlock');
