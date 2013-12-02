class selenium($version) {

  $directory="/usr/local/bin"
  $selenium_standalone="$directory/selenium-server-standalone.jar"

  case $operatingsystem {
    debian, ubuntu: {
      $supported = true
      $service_name = 'ntp'
      $conf_file    = 'ntp.conf.debian'
    }
    default: {
      $supported = false
      notify { "${module_name}_unsupported":
        message => "The ${module_name} module is not supported on ${::operatingsystem}",
      }
    }
  }

  if ($supported == true) {
    file { ["/opt/selenium", $directory]:
      ensure => "directory",
    }

    exec { "download" :
      command => "wget -O $directory/selenium-server-standalone-$version.jar  https://selenium.googlecode.com/files/selenium-server-standalone-$version.jar",
      path => "/usr/bin:/bin:/usr/sbin:/sbin",
      unless => "ls $directory | grep selenium-server-standalone-$version.jar",
      require => File[$directory],
    }

    file { $selenium_standalone :
      ensure => "present",
      source => "$directory/selenium-server-standalone-$version.jar",
      require => Exec["download"],
    }

    file { "/etc/init.d/selenium":
      source => "puppet:///modules/selenium/selenium.init",
      mode => 755,
    }

    file { "/etc/environment":
        content => inline_template("DISPLAY=:99")
    }
  }
}