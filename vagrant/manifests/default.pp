group { 'puppet': ensure => present }
Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }
File { owner => 0, group => 0, mode => 0644 }

file { "/var/lock/apache2":
  ensure => directory,
  owner => vagrant
}

exec { "ApacheUserChange" :
  command => "sed -i 's/export APACHE_RUN_USER=.*/export APACHE_RUN_USER=vagrant/ ; s/export APACHE_RUN_GROUP=.*/export APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars",
  require => [ Package["apache"], File["/var/lock/apache2"] ],
  notify  => Service['apache'],
}

class {'apt':
  always_apt_update => true,
}

Class['::apt::update'] -> Package <|
    title != 'python-software-properties'
and title != 'software-properties-common'
|>

    apt::key { '4F4EA0AAE5267A6C': }

apt::ppa { 'ppa:ondrej/php5-oldstable':
  require => Apt::Key['4F4EA0AAE5267A6C']
}

package { [
    'build-essential',
    'vim',
    'curl',
    'git-core',
    'mc'
  ]:
  ensure  => 'installed',
}

class { 'apache': }

apache::dotconf { 'custom':
  content => 'EnableSendfile Off',
}

apache::module { 'rewrite': }

apache::vhost { "fsi-admin-bundle.dev":
  server_name   => "fsi-admin-bundle.dev",
  serveraliases => [
    "www.fsi-admin-bundle.dev"
  ],
  docroot       => "/var/www/admin-bundle/features/fixtures/project/web",
  port          => '80',
  env_variables => [
],
  priority      => '1',
  notify  => Service['apache'],
}

class { 'php':
  service             => 'apache',
  service_autorestart => false,
  module_prefix       => '',
}

php::module { 'php5-sqlite': }
php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }
php::module { 'php5-gd': }
php::module { 'php-apc': }

class { 'composer':
  require => Package['php5', 'curl'],
}

puphpet::ini { 'php':
  value   => [
    'date.timezone = "Europe/Warsaw"'
  ],
  ini     => '/etc/php5/conf.d/zzz_php.ini',
  notify  => Service['apache'],
  require => Class['php'],
}

puphpet::ini { 'custom':
  value   => [
    'display_errors = On',
    'error_reporting = -1',
    'short_open_tag = 0',
  ],
  ini     => '/etc/php5/conf.d/zzz_custom.ini',
  notify  => Service['apache'],
  require => Class['php'],
}

class{ 'xvfb': }
class{ 'java': }
class{ 'selenium':
  version => "2.35.0",
  require => Class['java', 'xvfb'],
  notify  => Service['apache'],
}

package { [
    'firefox'
  ]:
  ensure  => 'installed',
}

service { "selenium":
    ensure => running,
    enable => true,
    hasstatus => true,
    hasrestart => true,
    require => Class['selenium']
}

