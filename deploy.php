<?php
namespace Deployer;

require 'recipe/laravel.php';

// Zone ühenduss
set('application', 'hajusrakweather');
set('remote_user', 'virt109715');
set('http_user', 'virt109715');
set('keep_releases', 2);

host('tak21vakkum.itmajakas.ee')
    ->setHostname('tak21vakkum.itmajakas.ee')
    ->set('http_user', 'virt109715')
    ->set('deploy_path', '~/domeenid/www.tak21vakkum.itmajakas.ee/hajusrakweather')
    ->set('branch', 'main');

set('repository', 'git@github.com:FluffyQuake/weather-apii.git');

// tasks
task('opcache:clear', function () {
    run('killall php82-cgi || true');
})->desc('Clear opcache');

task('build:node', function () {
    cd('{{release_path}}');
    run('npm i');
    run('npx vite build');
    run('rm -rf node_modules');
});

task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'build:node',
    'deploy:publish',
    'opcache:clear',
    'artisan:cache:clear'
]);
// Hooks

after('deploy:failed', 'deploy:unlock');