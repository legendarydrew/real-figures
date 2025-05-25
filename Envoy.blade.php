{{--
ENVOY deployment script
This script was adapted from the one used for deploying the main web site.
We want to upload it to a folder outside of public_html, then create a symlink
(In this case, silentmode.tv/real-figures).
--}}

@servers(['web' => [sprintf('%s@%s', $user, $host)], 'localhost' => ['127.0.0.1']])
{{-- The above must be on a single line.--}}

@setup
// Sanity checks for information.
$check_vars = ['host', 'user', 'path', 'run'];
foreach ($check_vars as $var) {
if (empty($$var)) {
exit("ERROR: \$$var parameter is empty or undefined.\n");
}
}

if (file_exists($path) || is_writable($path)) {
exit("ERROR: cannot access $path!");
}

// Ensure the given $path is a potential web directory (/home/* or /var/www/*).
// Note that the path parameter should be a full path.
if (!(preg_match("/(\/home\/|\/var\/www\/)/i", $path) === 1)) {
exit('ERROR: the provided $path doesn\'t look like a web directory.');
}

$current_release_dir = $path . '/current';
$releases_dir        = $path . '/releases';
$new_release_dir     = $releases_dir . '/' . $run . '-' . now()->format('YmdHis');
$keep_versions       = 3;

$remote              = sprintf('%s@%s:%s', $user, $host, $new_release_dir);

// Set the command used to represent PHP.
$php = $php ?: 'php';
@endsetup

@story('deploy')
rsync
verify_install
set_permissions
activate_release
optimise
migrate
content_update
cleanup
@endstory

@task('rsync', ['on' => 'localhost'])
echo "=> Deploying code from {{ $dir }} to {{ $remote }}..."
{{--
  https://explainshell.com/explain?cmd=rsync+-zrSlh+--exclude-from%3Ddeployment-exclude-list.txt+.%2F.+%7B%7B+%24remote+%7D%7D
  The "-a" flag was added due to the storage folder structure not being uploaded.
  We make sure to include the trailing forward slash, to copy the contents of the folder instead of the folder itself.
--}}
rsync -zrSlha --stats --exclude-from=deployment-exclude-list.txt {{ $dir }}/ {{ $remote }}

{{--
  The .env file has to be uploaded separately, as the above rsync excludes files (and folders?) beginning with ".".
  Note that the dot character is escaped.
--}}
rsync -za --verbose {{ $dir }}/\.env {{ $remote }}/\.env
@endtask

@task('verify_install', ['on' => 'web'])
echo "=> Verifying install ({{ $new_release_dir }})..."
{{-- This checks that we can run artisan, and I've added a check for the presence of the .env file. --}}
cd {{ $new_release_dir }}
{{ $php }} artisan --version
[[ -f {{ $new_release_dir }}/\.env ]] && echo ".env file is present." || echo ".env file is MISSING!"
{{ $php }} artisan key:generate -q
@endtask

@task('set_permissions', ['on' => 'web'])
echo "=> Setting file and folder permissions in ({{ $new_release_dir }})"
cd {{ $new_release_dir }}
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache/
@endtask

@task('activate_release', ['on' => 'web'])
echo "=> Activating new release ({{ $new_release_dir }} -> {{ $current_release_dir }})"
{{-- This changes the current folder symbolic link to the newly uploaded site version. --}}
ln -nfs {{ $new_release_dir }} {{ $current_release_dir }}
@endtask

@task('migrate', ['on' => 'web'])
echo '=> Running migrations'
cd {{ $new_release_dir }}
{{ $php }} artisan migrate --force
@endtask

@task('optimise', ['on' => 'web'])
echo '=> Clearing cache and optimising'
cd {{ $new_release_dir }}

{{ $php }} -d "disable_functions=" artisan optimize
{{-- route:cache will fail if any of the route definitions are closures. --}}
{{-- the list of disabled functions in PHP is temporarily emptied, as migrating requires proc_open() which is disabled.--}}
@endtask

@task('content_update', ['on' => 'web'])
cd {{ $new_release_dir }}

{{ $php }} artisan rt:superuser "{{ $su_username }}" "{{ $su_email }}" "{{ $su_password }}"
@endtask

@task('cleanup', ['on' => 'web'])
echo "=> Executing cleanup command in {{ $releases_dir }}"
{{--
This will remove old versions of the site from the releases folder.
The number of versions to keep is specified with the tail command (making sure we at least keep the newest one!).
--}}
ls -dt {{ $releases_dir }}/*/ | tail -n +{{ $keep_versions + 1 }} | xargs rm -rf
chmod -R 755 {{ $releases_dir }}
@endtask
