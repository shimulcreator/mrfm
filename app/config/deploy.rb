#default_run_options[:env] = { 'TERM' => 'xterm' }
set :application, "Maklarresurs"
set :domain,      "shimulrahman.com"
set :deploy_to,   "/var/www/Projects/maklarresurs.shimulrahman.com"
set :app_path,    "app"

set :repository,  "ssh://git@bitbucket.org/shimul/maklarresurs.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set   :use_sudo,      true
set   :keep_releases, 3


# Be more verbose by uncommenting the following line
#logger.level = Logger::MAX_LEVEL


set :symfony_env_prod,      "prod"

set :user,                  "root"
set :webserver_user,        "www-data"
set :keep_releases,         3
set :permission_method,     :chown
set :use_set_permissions,   true
set :use_composer,          true
set :composer_bin,          false
set :interactive_mode,      true

set :shared_files,          ["app/config/parameters.yml"]
set :shared_children,       ["app/logs", "vendor"]
set :writable_dirs,         ["app/cache", "app/logs"]

set :branch, "master"

set :dump_assetic_assets, true

ssh_options[:forward_agent] = true



after "deploy:create_symlink" do
    capifony_pretty_print "--> Restarting PHP5-FPM"
    stream "sudo sh -c 'service php5-fpm restart'"
    capifony_puts_ok
end